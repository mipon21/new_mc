<?php

namespace Botble\DHL\Providers;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\DHL\DHL;
use Botble\Ecommerce\Enums\ShippingMethodEnum;
use Botble\Ecommerce\Models\Shipment;
use Botble\Payment\Enums\PaymentMethodEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter('handle_shipping_fee', [$this, 'handleShippingFee'], 12, 2);

        add_filter(SHIPPING_METHODS_SETTINGS_PAGE, [$this, 'addSettings'], 3);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == ShippingMethodEnum::class) {
                $values['DHL'] = DHL_SHIPPING_METHOD_NAME;
            }

            return $values;
        }, 3, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == ShippingMethodEnum::class && $value == DHL_SHIPPING_METHOD_NAME) {
                return 'DHL';
            }

            return $value;
        }, 3, 2);

        add_filter('shipment_buttons_detail_order', function (?string $content, Shipment $shipment) {
            Assets::addScriptsDirectly('vendor/core/plugins/dhl/js/dhl.js');

            return $content . view('plugins/dhl::buttons', compact('shipment'))->render();
        }, 2, 2);
    }

    public function handleShippingFee(array $result, array $data): array
    {
        if (! $this->app->runningInConsole() && setting('shipping_dhl_status') == 1) {
            Arr::forget($data, 'extra.COD');
            $results = app(DHL::class)->getRates($data);
            if (Arr::get($data, 'payment_method') == PaymentMethodEnum::COD) {
                $rates = Arr::get($results, 'shipment.rates') ?: [];
                foreach ($rates as &$rate) {
                    $rate['disabled'] = true;
                    $rate['error_message'] = __('Not available in COD payment option.');
                }

                Arr::set($results, 'shipment.rates', $rates);
            }

            $result['dhl'] = Arr::get($results, 'shipment.rates') ?: [];
        }

        return $result;
    }

    public function addSettings(?string $settings): string
    {
        $logFiles = [];

        if (setting('shipping_dhl_logging')) {
            foreach (BaseHelper::scanFolder(storage_path('logs')) as $file) {
                if (Str::startsWith($file, 'dhl-')) {
                    $logFiles[] = $file;
                }
            }
        }

        return $settings . view('plugins/dhl::settings', compact('logFiles'))->render();
    }
} 