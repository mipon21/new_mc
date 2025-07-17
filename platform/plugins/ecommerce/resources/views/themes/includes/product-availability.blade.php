<div class="number-items-available">
    @if ($product->isOutOfStock())
        <span class="text-danger">{{ __('Out of stock') }}</span>
    @elseif ($product->with_storehouse_management)
        {{-- Custom logic for warehouse management --}}
        @if ($product->quantity > 0)
            <span class="text-success fw-medium fs-6">{{ trans('plugins/ecommerce::products.availability.in_stock_shipping') }}</span>
        @else
            <span class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.availability.build_for_you') }}</span>
        @endif
    @else
        @if (! $productVariation)
            <span class="text-danger">{{ __('Not available') }}</span>
        @else
            @if ($productVariation->isOutOfStock())
                <span class="text-danger">{{ __('Out of stock') }}</span>
            @elseif (! $productVariation->with_storehouse_management)
                <span class="text-success">{{ __('Available') }}</span>
            @elseif ($productVariation->with_storehouse_management)
                {{-- Custom logic for warehouse management --}}
                @if ($productVariation->quantity > 0)
                    <span class="text-success fw-medium fs-6">{{ trans('plugins/ecommerce::products.availability.in_stock_shipping') }}</span>
                @else
                    <span class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.availability.build_for_you') }}</span>
                @endif
           @endif
       @endif
    @endif
</div>
