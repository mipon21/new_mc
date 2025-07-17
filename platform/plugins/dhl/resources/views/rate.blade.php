@foreach ($rates as $index => $rate)
    <div class="d-flex align-items-center mb-3">
        <div class="me-3">
            <input type="radio"
                name="dhl-radio-option"
                id="shipping-method-dhl-{{ $index }}"
                @disabled($rate['disabled'] ?? false)
                @checked($checked === $index)
                value="{{ $rate['id'] }}"
                data-id="{{ $rate['id'] }}"
                data-object_id="{{ $rate['object_id'] }}"
                data-object="{{ $rate['object_type'] }}"
                data-amount="{{ $rate['amount'] }}"
                data-service-name="{{ $rate['service_name'] }}"
                data-service-type="{{ $rate['service_type'] }}"
            >
        </div>
        <label for="shipping-method-dhl-{{ $index }}">
            <div class="row">
                <div class="col-auto">
                    <span>{{ $rate['service_name'] }}</span>
                </div>
                <div class="col-auto d-flex flex-column">
                    <strong>{{ $rate['price'] }}</strong>
                    @if ($rate['days'])
                        <small class="text-secondary">Estimated delivery: {{ $rate['days'] }} day(s)</small>
                    @endif
                </div>
            </div>
            @if ($rate['disabled'] ?? false)
                <small class="text-danger">{{ $rate['error_message'] ?? null }}</small>
            @endif
        </label>
    </div>
@endforeach 