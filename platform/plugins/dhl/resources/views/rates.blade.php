<form action="{{ route(app(\Botble\DHL\DHL::class)->getRoutePrefixByFactor() . 'dhl.update-rate', $shipment->id) }}" method="post">
    @csrf
    <input type="hidden" name="rate_id" value="">
    <input type="hidden" name="shipment_id" value="">
    <input type="hidden" name="tracking_id" value="">
    <input type="hidden" name="metadata" value="">
    <input type="hidden" name="service_name" value="">
    <input type="hidden" name="amount" value="">

    @include('plugins/dhl::rate', [
        'rates' => array_slice($rates, 0, 1),
        'shipment' => $shipment,
        'checked' => 0,
    ])

    @if (empty($rates))
        <p>No shipping rates found.</p>
    @endif

    <div class="row mt-2 justify-content-between">
        <div class="col-auto">
            @if (count($rates) > 1)
                <a href="#"
                    class="text-primary d-inline-block mt-2 mb-2 get-dhl-rates"
                    data-key="#"
                    data-bs-toggle="collapse"
                    data-bs-target="#other-dhl-rates"
                    aria-expanded="false"
                    aria-controls="other-dhl-rates"
                >
                    <span>View {{ count($rates) }} other rates</span>
                    <i class="fas fa-angle-down d-inline-block"></i>
                </a>

                <div class="collapse" id="other-dhl-rates">
                    @include('plugins/dhl::rate', [
                        'rates' => array_slice($rates, 1),
                        'shipment' => $shipment,
                        'checked' => null,
                    ])
                </div>
            @endif
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Update Rate</button>
        </div>
    </div>
</form>
<div class="btn-list mt-2 gap-2 row justify-content-between">
    <div class="col-auto">
        <button class="btn btn-warning get-dhl-rates">Refresh Rates</button>
    </div>
    <div class="col-auto">
        <button class="btn btn-primary create-transaction">Generate Label</button>
    </div>
</div> 