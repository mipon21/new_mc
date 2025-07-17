<div class="shipment-actions">
    @if ($shipment->status == \Botble\Ecommerce\Enums\ShippingStatusEnum::PENDING)
        <button class="btn btn-info btn-trigger-update-shipping-status">
            {{ trans('plugins/ecommerce::shipping.update_shipping_status') }}
        </button>
    @endif

    @if (empty($shipment->tracking_id))
        <div class="btn-list mt-2 gap-2">
            <button class="btn btn-primary btn-sm btn-info"
                data-bs-toggle="modal"
                data-bs-target="#dhl-view-n-create-transaction"
                @php
                    $routeName = \Route::has('marketplace.vendor.dhl.show') ? 'marketplace.vendor.dhl.show' : 'ecommerce.shipments.dhl.show';
                @endphp
                data-url="{{ route($routeName, $shipment->id) }}"
            >
                Get rates
            </button>
        </div>
    @endif
</div>

<div class="modal fade" id="dhl-view-n-create-transaction" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="til_img"></i>
                    <strong>Get rates</strong>
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div> 