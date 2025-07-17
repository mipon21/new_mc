<?php

namespace Botble\DHL\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\DHL\DHL;
use Botble\Ecommerce\Models\Shipment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class DHLController extends BaseController
{
    public function show(int|string $id, BaseHttpResponse $response)
    {
        $shipment = Shipment::query()->findOrFail($id);

        $this->pageTitle('Get shipping rate');

        $dhl = app(DHL::class);

        return $response->setData([
            'html' => view('plugins/dhl::detail', compact('shipment', 'dhl'))->render(),
        ]);
    }

    public function getRates(int|string $id, BaseHttpResponse $response)
    {
        $shipment = Shipment::query()->findOrFail($id);

        $dhl = app(DHL::class);

        $result = $dhl->getRates([
            'address_from' => $shipment->store->country . ', ' . $shipment->store->state . ', ' . $shipment->store->city,
            'address_to' => $shipment->order_address->country . ', ' . $shipment->order_address->state . ', ' . $shipment->order_address->city,
            'weight' => $shipment->weight,
            'order_total' => $shipment->cod_amount ?: ($shipment->price),
        ]);

        $rates = $result['shipment']['rates'] ?? [];

        if (empty($rates)) {
            return $response
                ->setError()
                ->setMessage('No shipping rates found.');
        }

        return $response
            ->setData([
                'html' => view('plugins/dhl::rates', compact('rates', 'shipment', 'dhl'))->render(),
            ]);
    }

    public function updateRate(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $shipment = Shipment::query()->findOrFail($id);

        $shipment->update([
            'rate_id' => $request->input('rate_id'),
            'service_name' => $request->input('service_name'),
            'shipping_amount' => $request->input('amount'),
            'tracking_id' => $request->input('tracking_id'),
            'shipment_id' => $request->input('shipment_id'),
            'metadata' => $request->input('metadata'),
        ]);

        return $response
            ->setPreviousUrl(route('ecommerce.shipments.index'))
            ->setNextUrl(route('ecommerce.shipments.edit', $shipment->id))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function createTransaction(int|string $id, BaseHttpResponse $response)
    {
        $shipment = Shipment::query()->findOrFail($id);

        $dhl = app(DHL::class);

        $result = $dhl->createShipment($shipment);

        if (Arr::get($result, 'error')) {
            return $response
                ->setError()
                ->setMessage(Arr::get($result, 'message', 'Something went wrong!'));
        }

        return $response
            ->setPreviousUrl(route('ecommerce.shipments.index'))
            ->setNextUrl(route('ecommerce.shipments.edit', $shipment->id))
            ->setMessage('Transaction generated successfully!');
    }

    public function viewLog(string $file, BaseHttpResponse $response)
    {
        $logFile = storage_path('logs/' . $file);

        if (! File::exists($logFile)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.file_not_exists', ['file' => $logFile]));
        }

        try {
            $content = BaseHelper::getFileData($logFile, false);
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }

        return $response->setData(view('plugins/dhl::logs', compact('content'))->render());
    }
} 