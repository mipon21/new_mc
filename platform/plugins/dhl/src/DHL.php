<?php

namespace Botble\DHL;

use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\Shipment;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Location\Models\Country;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Support\Services\Cache\Cache;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DHL
{
    protected string $apiKey;
    protected string $apiEndpoint;
    protected bool $sandbox;
    protected bool $logging;
    protected bool $useCache;
    protected $logger;
    protected $cache;
    protected string $currency;
    protected bool $insurance;
    protected bool $signature;
    protected bool $validateAddress;
    protected array $origin;

    public function __construct()
    {
        $this->sandbox = setting('shipping_dhl_sandbox', 1) == 1;
        $this->apiKey = setting('shipping_dhl_test_key') ?: '';
        $this->apiEndpoint = setting('shipping_dhl_api_endpoint', 'https://express.api.dhl.com/mydhlapi/test');
        
        if (! $this->sandbox) {
            $this->apiKey = setting('shipping_dhl_production_key') ?: '';
            $this->apiEndpoint = str_replace('/test', '', $this->apiEndpoint);
        }

        $this->useCache = setting('shipping_dhl_cache_response', 1);
        $this->logging = setting('shipping_dhl_logging', 1);

        if ($this->logging) {
            $this->logger = Log::channel('dhl');
        }

        $this->cache = Cache::make('dhl');
        
        $this->currency = get_application_currency()->title;
        $this->insurance = false;
        $this->signature = false;
        $this->validateAddress = false;
        
        // Get origin address from EcommerceHelper
        $this->origin = $this->mergeAddress(\Botble\Ecommerce\Facades\EcommerceHelper::getOriginAddress());
    }

    public function getName(): string
    {
        return 'DHL';
    }
    
    /**
     * Merge address fields to match DHL API format
     */
    public function mergeAddress(array $address): array
    {
        return array_merge($address, [
            'street1' => Arr::get($address, 'address'),
            'street2' => Arr::get($address, 'address_2'),
            'zip' => Arr::get($address, 'zip_code'),
        ]);
    }

    public function validate(): array
    {
        $errors = [];
        $apiTokenName = $this->sandbox ? 'Test API Key' : 'Production API Key';

        if (! $this->apiKey) {
            $errors[] = $apiTokenName . ' is required';
        } else {
            try {
                // Test API connection
                $client = new Client();
                $response = $client->get($this->apiEndpoint . '/rates', [
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode($this->apiKey),
                        'Accept' => 'application/json',
                    ],
                ]);

                if ($response->getStatusCode() != 200) {
                    $errors[] = $apiTokenName . ' is invalid';
                }
            } catch (Exception | GuzzleException $exception) {
                if ($this->logging) {
                    $this->logger->error('DHL: ' . $exception->getMessage());
                }
                $errors[] = $exception->getMessage();
            }
        }

        return $errors;
    }

    public function getRates(array $data): array
    {
        $orderTotal = Arr::get($data, 'order_total', 0);
        $weight = Arr::get($data, 'weight', 0);
        $weight = $weight ?: 0.1;
        $orderTotal = $orderTotal ?: 0;

        $paymentMethod = Arr::get($data, 'payment_method');

        $endpoint = $this->apiEndpoint . '/rates';

        $address = null;
        $addressTo = null;

        $addressFrom = Arr::get($data, 'address_from');
        if ($addressFrom) {
            $address = $addressFrom;
        }

        $addressTo = Arr::get($data, 'address_to');

        if (! $addressFrom && ! $addressTo) {
            if ($this->logging) {
                $this->logger->error('DHL: Address from and address to are required');
            }
            return [];
        }

        $cacheKey = md5(serialize([$address, $addressTo, $weight, $orderTotal, $paymentMethod]));

        if ($this->useCache && $this->cache->has($cacheKey)) {
            $result = $this->cache->get($cacheKey);
            if ($result) {
                return $result;
            }
        }

        $fromCountryCode = null;
        $toCountryCode = null;

        if ($address) {
            if (is_object($address)) {
                $country = Country::query()->find($address->country);
                if ($country) {
                    $fromCountryCode = $country->code;
                }
            } else {
                $fromCountryCode = Arr::get($address, 'country');
            }
        }

        if ($addressTo) {
            if (is_object($addressTo)) {
                $country = Country::query()->find($addressTo->country);
                if ($country) {
                    $toCountryCode = $country->code;
                }
            } else {
                $toCountryCode = Arr::get($addressTo, 'country');
            }
        }

        if (! $fromCountryCode || ! $toCountryCode) {
            if ($this->logging) {
                $this->logger->error('DHL: Country code is required for both sender and recipient');
            }
            return [];
        }

        // Build the request data
        $requestData = [
            'plannedShippingDateAndTime' => now()->format('Y-m-d\TH:i:s \G\M\T'),
            'unitOfMeasurement' => 'metric',
            'isCustomsDeclarable' => false,
            'accounts' => [
                [
                    'typeCode' => 'shipper',
                    'number' => '123456789'
                ]
            ],
            'productCode' => 'P',
            'localProductCode' => 'P',
            'valueAddedServices' => [
                [
                    'serviceCode' => 'II',
                    'localServiceCode' => 'II',
                    'value' => $orderTotal,
                    'currency' => 'USD',
                ]
            ],
            'customerDetails' => [
                'shipperDetails' => [
                    'postalCode' => is_object($address) ? $address->zip_code : Arr::get($address, 'zip_code'),
                    'cityName' => is_object($address) ? $address->city : Arr::get($address, 'city'),
                    'countryCode' => $fromCountryCode,
                ],
                'receiverDetails' => [
                    'postalCode' => is_object($addressTo) ? $addressTo->zip_code : Arr::get($addressTo, 'zip_code'),
                    'cityName' => is_object($addressTo) ? $addressTo->city : Arr::get($addressTo, 'city'),
                    'countryCode' => $toCountryCode,
                ],
            ],
            'content' => [
                'packages' => [
                    [
                        'weight' => $weight,
                        'dimensions' => [
                            'length' => 10,
                            'width' => 10,
                            'height' => 10,
                        ],
                    ],
                ],
            ],
        ];

        try {
            $client = new Client();
            $response = $client->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->apiKey),
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestData,
            ]);

            if ($response->getStatusCode() == 200) {
                $body = $response->getBody()->getContents();
                $data = json_decode($body, true);
                
                $result = [
                    'shipment' => [
                        'rates' => $this->formatRates($data),
                    ],
                ];

                if ($this->useCache) {
                    $this->cache->put($cacheKey, $result);
                }

                return $result;
            }
        } catch (Exception | GuzzleException $exception) {
            if ($this->logging) {
                $this->logger->error('DHL: ' . $exception->getMessage());
            }
        }

        return [];
    }

    protected function formatRates(array $rates): array
    {
        $formattedRates = [];
        $products = Arr::get($rates, 'products', []);
        foreach ($products as $index => $product) {
            $serviceCode = Arr::get($product, 'productCode');
            $serviceName = Arr::get($product, 'productName');
            $totalAmount = Arr::get($product, 'totalPrice.0.price', 0);
            $currencyCode = Arr::get($product, 'totalPrice.0.currencyCode', 'USD');
            $transitDays = Arr::get($product, 'deliveryCapabilities.estimatedDeliveryDateAndTime');
            
            // Calculate transit days based on estimated delivery time
            $days = 1;
            if ($transitDays) {
                $deliveryDate = Carbon::parse($transitDays);
                $today = Carbon::now();
                $days = $deliveryDate->diffInDays($today);
                $days = max(1, $days);
            }
            
            $description = 'Estimated delivery: ' . $days . ' day(s)';
            
            $formattedRates[] = [
                'id' => 'dhl_' . $serviceCode . '_' . Str::random(6),
                'object_id' => 'dhl_rate_' . $index,
                'object_type' => $this->getName(),
                'service_type' => $serviceCode,
                'service_name' => $serviceName,
                'description' => $description,
                'carrier_account' => $this->getName(),
                'days' => $days,
                'amount' => $totalAmount,
                'currency' => $currencyCode,
                'price' => format_price($totalAmount, null, true),
            ];
        }
        
        return $formattedRates;
    }

    public function createShipment(Shipment $shipment): array
    {
        if (! $shipment) {
            if ($this->logging) {
                $this->logger->error('DHL: Shipment is required');
            }
            return [
                'error' => true,
                'message' => 'Shipment is required',
            ];
        }
        
        $order = $shipment->order;
        
        if (!$order) {
            if ($this->logging) {
                $this->logger->error('DHL: Order is required for shipment');
            }
            return [
                'error' => true,
                'message' => 'Order is required for shipment',
            ];
        }
        
        // Get selected rate ID from shipment metadata
        $rateId = $shipment->metadata['dhl_rate_id'] ?? null;
        
        if (!$rateId) {
            if ($this->logging) {
                $this->logger->error('DHL: Rate ID is required for creating shipment');
            }
            return [
                'error' => true,
                'message' => 'Rate ID is required for creating shipment',
            ];
        }
        
        try {
            // Prepare shipment data
            $originAddress = $this->getAddressData($order->address);
            $destinationAddress = $this->getAddressData($order->shippingAddress);
            
            // Get order items for customs declaration if international
            $items = $order->products->map(function ($item) {
                return [
                    'description' => $item->product_name,
                    'quantity' => $item->qty,
                    'weight' => $item->weight,
                    'price' => $item->price,
                ];
            })->toArray();
            
            // Calculate total weight
            $totalWeight = $order->products->sum(function ($item) {
                return $item->weight * $item->qty;
            });
            
            // Format addresses for DHL API
            $shipperDetails = [
                'postalCode' => $originAddress['zip_code'],
                'cityName' => $originAddress['city'],
                'countryCode' => $originAddress['country_code'],
                'addressLine1' => $originAddress['address'],
                'addressLine2' => $originAddress['address_2'] ?? '',
                'personName' => $originAddress['name'],
                'phoneNumber' => $originAddress['phone'],
                'emailAddress' => $originAddress['email'] ?? '',
            ];
            
            $receiverDetails = [
                'postalCode' => $destinationAddress['zip_code'],
                'cityName' => $destinationAddress['city'],
                'countryCode' => $destinationAddress['country_code'],
                'addressLine1' => $destinationAddress['address'],
                'addressLine2' => $destinationAddress['address_2'] ?? '',
                'personName' => $destinationAddress['name'],
                'phoneNumber' => $destinationAddress['phone'],
                'emailAddress' => $destinationAddress['email'] ?? '',
            ];
            
            // Check if international shipment
            $isInternational = $originAddress['country_code'] !== $destinationAddress['country_code'];
            
            // Prepare request data for shipment creation
            $requestData = [
                'plannedShippingDateAndTime' => Carbon::now()->format('Y-m-d\TH:i:s \G\M\T'),
                'pickup' => [
                    'isRequested' => false,
                ],
                'productCode' => Str::after($rateId, 'dhl_'),
                'outputImageProperties' => [
                    'printerDPI' => 300,
                    'imageOptions' => [
                        [
                            'typeCode' => 'WAYBILL',
                            'templateName' => 'ECOM26_A4_001',
                            'isRequested' => true,
                        ],
                        [
                            'typeCode' => 'LABEL',
                            'templateName' => 'ECOM26_84_001',
                            'isRequested' => true,
                        ],
                    ],
                ],
                'customerDetails' => [
                    'shipperDetails' => $shipperDetails,
                    'receiverDetails' => $receiverDetails,
                ],
                'content' => [
                    'packages' => [
                        [
                            'weight' => max(0.1, $totalWeight),
                            'dimensions' => [
                                'length' => 20,
                                'width' => 20,
                                'height' => 10,
                            ],
                            'customerReferences' => [
                                [
                                    'value' => 'Order #' . $order->code,
                                ],
                            ],
                        ],
                    ],
                ],
                'valueAddedServices' => [],
                'customerReferences' => [
                    [
                        'typeCode' => 'CU',
                        'value' => 'Order #' . $order->code,
                    ],
                ],
            ];
            
            // Add customs declaration for international shipments
            if ($isInternational) {
                $requestData['isCustomsDeclarable'] = true;
                $requestData['customsDetails'] = $this->prepareCustomsInfo($items, $order->amount, $originAddress['country_code']);
            } else {
                $requestData['isCustomsDeclarable'] = false;
            }
            
            // Add insurance if needed
            if ($this->insurance) {
                $requestData['valueAddedServices'][] = [
                    'serviceCode' => 'II',
                    'localServiceCode' => 'II',
                    'value' => $order->amount,
                    'currency' => $this->currency,
                ];
            }
            
            // Add signature confirmation if needed
            if ($this->signature) {
                $requestData['valueAddedServices'][] = [
                    'serviceCode' => 'SA',
                    'localServiceCode' => 'SA',
                ];
            }
            
            // Create shipment through DHL API
            $client = new Client();
            $response = $client->post($this->apiEndpoint . '/shipments', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->apiKey),
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestData,
            ]);
            
            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 201) {
                $body = $response->getBody()->getContents();
                $data = json_decode($body, true);
                
                // Store tracking number in shipment
                $trackingNumber = Arr::get($data, 'shipmentTrackingNumber');
                $shipment->tracking_id = $trackingNumber;
                $shipment->tracking_link = $this->getTrackingUrl($trackingNumber);
                
                // Store label URL
                $documents = Arr::get($data, 'documents', []);
                $labelContent = null;
                $labelType = null;
                
                foreach ($documents as $document) {
                    if (Arr::get($document, 'typeCode') == 'LABEL') {
                        $labelContent = Arr::get($document, 'content');
                        $labelType = Arr::get($document, 'typeCode');
                        break;
                    }
                }
                
                if ($labelContent) {
                    // Save the label to storage
                    $labelPath = 'shipments/dhl/' . $trackingNumber . '.pdf';
                    Storage::disk('public')->put($labelPath, base64_decode($labelContent));
                    
                    // Store label URL in metadata
                    $shipment->metadata = array_merge($shipment->metadata ?: [], [
                        'label_url' => Storage::disk('public')->url($labelPath),
                        'dhl_rate_id' => $rateId,
                        'dhl_shipment_id' => Arr::get($data, 'shipmentId'),
                    ]);
                }
                
                $shipment->save();
                
                return [
                    'error' => false,
                    'message' => 'Shipment created successfully',
                    'data' => [
                        'tracking_number' => $trackingNumber,
                        'label_url' => $shipment->metadata['label_url'] ?? null,
                    ],
                ];
            } else {
                if ($this->logging) {
                    $this->logger->error('DHL: Failed to create shipment - ' . $response->getBody()->getContents());
                }
                
                return [
                    'error' => true,
                    'message' => 'Failed to create shipment',
                ];
            }
            
        } catch (Exception | GuzzleException $exception) {
            if ($this->logging) {
                $this->logger->error('DHL: ' . $exception->getMessage());
            }
            
            return [
                'error' => true,
                'message' => $exception->getMessage(),
            ];
        }
    }
    
    /**
     * Get tracking information from DHL API
     */
    public function getTrackingInfo(string $trackingNumber): array
    {
        if (empty($trackingNumber)) {
            return [
                'error' => true,
                'message' => 'Tracking number is required',
            ];
        }
        
        $cacheKey = 'dhl_tracking_' . $trackingNumber;
        
        if ($this->useCache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }
        
        try {
            $client = new Client();
            $response = $client->get($this->apiEndpoint . '/shipments/tracking', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->apiKey),
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'trackingNumber' => $trackingNumber,
                ],
            ]);
            
            if ($response->getStatusCode() == 200) {
                $body = $response->getBody()->getContents();
                $data = json_decode($body, true);
                
                $result = [
                    'error' => false,
                    'data' => $this->formatTrackingData($data),
                ];
                
                if ($this->useCache) {
                    // Cache for a shorter time (15 minutes) as tracking status can change
                    $this->cache->put($cacheKey, $result, 15);
                }
                
                return $result;
            }
            
            return [
                'error' => true,
                'message' => 'Failed to retrieve tracking information',
            ];
            
        } catch (Exception | GuzzleException $exception) {
            if ($this->logging) {
                $this->logger->error('DHL Tracking: ' . $exception->getMessage());
            }
            
            return [
                'error' => true,
                'message' => $exception->getMessage(),
            ];
        }
    }
    
    /**
     * Format tracking data from DHL API response
     */
    protected function formatTrackingData(array $trackingData): array
    {
        $formattedData = [
            'tracking_number' => Arr::get($trackingData, 'shipmentTrackingNumber'),
            'status' => Arr::get($trackingData, 'status'),
            'estimated_delivery' => Arr::get($trackingData, 'estimatedDeliveryDate'),
            'events' => [],
        ];
        
        $events = Arr::get($trackingData, 'events', []);
        
        foreach ($events as $event) {
            $formattedData['events'][] = [
                'date' => Carbon::parse(Arr::get($event, 'timestamp'))->format('Y-m-d H:i:s'),
                'description' => Arr::get($event, 'description'),
                'location' => Arr::get($event, 'location.address.addressLocality'),
                'status' => Arr::get($event, 'statusCode'),
            ];
        }
        
        return $formattedData;
    }
    
    /**
     * Get address data in standard format
     */
    protected function getAddressData($address): array
    {
        if (!$address) {
            return [];
        }
        
        $country = Country::query()->find($address->country);
        $countryCode = $country ? $country->code : null;
        
        return [
            'name' => $address->name,
            'phone' => $address->phone,
            'email' => $address->email ?? '',
            'address' => $address->address,
            'address_2' => $address->address_2 ?? '',
            'city' => $address->city,
            'state' => $address->state,
            'zip_code' => $address->zip_code,
            'country_code' => $countryCode,
        ];
    }
    
    /**
     * Prepare customs information for international shipments
     */
    protected function prepareCustomsInfo(array $items, float $totalValue, string $originCountry): array
    {
        $customsItems = [];
        
        foreach ($items as $item) {
            $customsItems[] = [
                'description' => Str::limit($item['description'], 50),
                'countryOfOrigin' => $originCountry,
                'quantity' => [
                    'value' => $item['quantity'],
                    'unitOfMeasurement' => 'PCS',
                ],
                'unitPrice' => $item['price'],
                'customsValue' => $item['price'] * $item['quantity'],
                'currency' => $this->currency,
                'weight' => [
                    'netValue' => $item['weight'],
                    'grossValue' => $item['weight'],
                ],
            ];
        }
        
        return [
            'isExportDeclarable' => true,
            'declarationNote' => 'Commercial goods for sale',
            'exportDeclaration' => [
                'lineItems' => $customsItems,
                'invoice' => [
                    'number' => 'INV-' . time(),
                    'date' => Carbon::now()->format('Y-m-d'),
                    'totalNetWeight' => array_sum(array_map(function ($item) {
                        return $item['weight'] * $item['quantity'];
                    }, $items)),
                    'totalGrossWeight' => array_sum(array_map(function ($item) {
                        return $item['weight'] * $item['quantity'];
                    }, $items)),
                ],
                'exportReason' => 'SALE',
                'exportReasonType' => 'PERMANENT',
                'placeOfIncoterm' => 'Origin',
            ],
        ];
    }
    
    /**
     * Check if a shipment can be processed with DHL
     */
    public function canCreateTransaction(Shipment $shipment): bool
    {
        $order = $shipment->order;
        if (
            $order
            && $order->id
            && $order->shipping_method->getValue() == 'dhl'
            && $order->status != OrderStatusEnum::CANCELED
            && ! in_array($shipment->status->getValue(), [
                ShippingStatusEnum::CANCELED,
                ShippingStatusEnum::DELIVERING,
                ShippingStatusEnum::DELIVERED,
                ShippingStatusEnum::NOT_DELIVERED,
            ])
        ) {
            return true;
        }

        return false;
    }
    
    /**
     * Get tracking URL for a shipment
     */
    public function getTrackingUrl(string $trackingNumber): string
    {
        if ($this->sandbox) {
            return 'https://tracking.dhl.com/tracking/simulation?ref=' . $trackingNumber;
        }
        
        return 'https://tracking.dhl.com/tracking?ref=' . $trackingNumber;
    }

    public function getRoutePrefixByFactor(): string
    {
        // For marketplace plugin
        if (is_plugin_active('marketplace') && auth('customer')->check()) {
            return 'marketplace.vendor.';
        }
        
        return 'ecommerce.shipments.';
    }
} 