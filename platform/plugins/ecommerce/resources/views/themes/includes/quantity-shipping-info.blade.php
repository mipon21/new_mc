@php
    $quantityShippingService = app(\Botble\Ecommerce\Services\QuantityBasedShippingService::class);
    $isQuantityShippingEnabled = $quantityShippingService->isQuantityBasedShippingEnabled();
@endphp

@if ($isQuantityShippingEnabled)
    <div class="quantity-shipping-info">
        @php
            $cartItems = Cart::instance('cart')->content();
            $totalQuantity = $cartItems->sum('qty');
            $shippingBreakdown = $quantityShippingService->getShippingFeeBreakdown();
            $recommendation = $quantityShippingService->getRecommendedQuantity($totalQuantity);
        @endphp

        <div class="shipping-quantity-summary mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">{{ __('Total Items') }}:</span>
                <strong>{{ $totalQuantity }} {{ trans('plugins/ecommerce::shipping.quantity_unit') }}</strong>
            </div>

            @php
                $currentFee = $quantityShippingService->calculateShippingFee($totalQuantity);
            @endphp

            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">{{ __('Shipping Fee') }}:</span>
                <strong class="text-primary">{{ format_price($currentFee) }}</strong>
            </div>
        </div>

        @if ($recommendation)
            <div class="shipping-recommendation alert alert-info">
                <i class="fas fa-lightbulb me-2"></i>
                <strong>{{ __('Shipping Tip') }}:</strong> {{ $recommendation['message'] }}
                <div class="mt-2 small">
                    <div>{{ __('Current shipping') }}: {{ format_price($recommendation['current_fee']) }}</div>
                    <div>{{ __('New shipping') }}: {{ format_price($recommendation['new_fee']) }}</div>
                    <div class="text-success">{{ __('You save') }}: {{ format_price($recommendation['savings']) }}</div>
                </div>
            </div>
        @endif

        <div class="shipping-rates-table mt-3">
            <h6>{{ __('Shipping Rates by Quantity') }}</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Quantity Range') }}</th>
                            <th>{{ __('Shipping Fee') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shippingBreakdown as $breakdown)
                            <tr @class(['table-success' => strpos($breakdown['range'], (string) $totalQuantity) !== false])>
                                <td>{{ $breakdown['range'] }}</td>
                                <td>
                                    <strong>{{ $breakdown['fee'] }}</strong>
                                    @if ($breakdown['fee'] === format_price(0))
                                        <span class="badge bg-success ms-1">{{ __('FREE') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="shipping-benefits mt-3">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                {{ __('Shipping costs are calculated based on the total number of items in your order.') }}
            </small>
        </div>
    </div>

    <style>
        .quantity-shipping-info {
            border: 1px solid #e9ecef;
            border-radius: 0.375rem;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .shipping-recommendation {
            border-left: 4px solid #0dcaf0;
        }

        .shipping-rates-table .table {
            margin-bottom: 0;
        }

        .shipping-rates-table th {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .shipping-benefits {
            border-top: 1px solid #dee2e6;
            padding-top: 0.75rem;
        }
    </style>
@endif