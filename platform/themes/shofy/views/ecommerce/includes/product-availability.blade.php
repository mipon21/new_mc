<div class="number-items-available">
    @if ($product->stock_status == 'on_backorder')
        <p class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.stock_statuses.on_backorder') }}</p>
    @elseif ($product->stock_status == 'in_stock_with_shipping')
        <p class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.stock_statuses.in_stock_with_shipping', ['weeks' => $product->shipping_weeks]) }}</p>
    @elseif ($product->isOutOfStock())
        <span class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.stock_statuses.out_of_stock') }}</span>
    @elseif ($product->with_storehouse_management)
        {{-- Custom logic for warehouse management --}}
        @if ($product->quantity > 0)
            <span class="text-success fw-medium fs-6">{{ trans('plugins/ecommerce::products.availability.in_stock_shipping') }}</span>
        @else
            <span class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.availability.build_for_you') }}</span>
        @endif
    @else
        @if (! $productVariation)
            <span class="text-danger">{{ __('Not available') }}
        @else
            @if ($productVariation->stock_status == 'on_backorder')
                <p class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.stock_statuses.on_backorder') }}</p>
            @elseif ($productVariation->stock_status == 'in_stock_with_shipping')
                <p class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.stock_statuses.in_stock_with_shipping', ['weeks' => $productVariation->shipping_weeks]) }}</p>
            @elseif ($productVariation->isOutOfStock())
                <span class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.stock_statuses.out_of_stock') }}</span>
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
