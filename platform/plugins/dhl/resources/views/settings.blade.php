@php
$status = setting('shipping_dhl_status', 0);
$testKey = setting('shipping_dhl_test_key') ?: '';
$prodKey = setting('shipping_dhl_production_key') ?: '';
$test = setting('shipping_dhl_sandbox', 1) ?: 0;
$logging = setting('shipping_dhl_logging', 1) ?: 0;
$cacheResponse = setting('shipping_dhl_cache_response', 1) ?: 0;
$webhook = setting('shipping_dhl_webhooks', 1) ?: 0;
@endphp

<div class="shipping-provider-item card mt-2 mb-2">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <div class="shipping-logo thumbnail">
                    <span class="fw-bold text-danger">DHL</span>
                </div>
            </div>
            <div class="col-auto">
                <a href="https://dhl.com/" target="_blank" class="fw-semibold">DHL</a>
                <p class="mb-0">DHL Express Shipping integration for eCommerce.</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-start mb-2">
            <div class="d-flex align-items-center">
                <label class="ws-nm inline-display method-name-label">DHL</label>
            </div>
            <div class="d-flex align-items-center">
                <label class="form-check-label ms-2">
                    <input type="radio" class="form-check-input" name="shipping_dhl_status" value="1" @checked($status)>
                    {{ trans('core/base::forms.yes') }}
                </label>
            </div>
            <div class="d-flex align-items-center">
                <label class="form-check-label ms-2">
                    <input type="radio" class="form-check-input" name="shipping_dhl_status" value="0" @checked(!$status)>
                    {{ trans('core/base::forms.no') }}
                </label>
            </div>
        </div>
        <div data-bs-toggle="collapse" href="#collapse-shipping-method-dhl" role="button" aria-expanded="false" aria-controls="collapse-shipping-method-dhl" class="d-flex justify-content-between cursor-pointer">
            <span>
                <i class="fas fa-exchange-alt me-1"></i>
                <span>Configuration</span>
            </span>
            <i class="fas fa-angle-down"></i>
        </div>
        <div class="collapse mt-3 p-2" id="collapse-shipping-method-dhl">
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <input type="checkbox" name="shipping_dhl_sandbox" value="1" @checked($test) class="form-check-input me-1">
                        <label class="form-check-label">Use sandbox mode</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <input type="checkbox" name="shipping_dhl_logging" value="1" @checked($logging) class="form-check-input me-1">
                        <label class="form-check-label">Enable debug log</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <input type="checkbox" name="shipping_dhl_cache_response" value="1" @checked($cacheResponse) class="form-check-input me-1">
                        <label class="form-check-label">Cache API response</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <input type="checkbox" name="shipping_dhl_webhooks" value="1" @checked($webhook) class="form-check-input me-1">
                        <label class="form-check-label">Enable webhooks</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label" for="shipping_dhl_test_key">Test API Key</label>
                        <input type="text" name="shipping_dhl_test_key" id="shipping_dhl_test_key" class="form-control" value="{{ $testKey }}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label" for="shipping_dhl_production_key">Production API Key</label>
                        <input type="text" name="shipping_dhl_production_key" id="shipping_dhl_production_key" class="form-control" value="{{ $prodKey }}">
                    </div>
                </div>

                @if (isset($logFiles) && count($logFiles))
                    <div class="col-sm-12">
                        <h6>Logs</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">{{ trans('core/base::tables.filename') }}</th>
                                    <th scope="col">{{ trans('core/base::tables.size') }}</th>
                                    <th scope="col">{{ trans('core/base::tables.created_at') }}</th>
                                    <th scope="col">{{ trans('core/base::tables.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($logFiles as $file)
                                    <tr>
                                        <td>{{ $file }}</td>
                                        <td>{{ human_file_size(file_size(storage_path('logs/' . $file))) }}</td>
                                        <td>{{ Carbon\Carbon::createFromTimestamp(filectime(storage_path('logs/' . $file)))->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="#" class="text-success btn-view-log-file" data-file="{{ $file }}" data-url="{{ route(app(\Botble\DHL\DHL::class)->getRoutePrefixByFactor() . 'dhl.view-log', $file) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 