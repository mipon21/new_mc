<div class="number-items-available">
    @if ($product->stock_status == 'on_backorder')
        <p class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.stock_statuses.on_backorder') }}</p>
    @elseif ($product->stock_status == 'in_stock_with_shipping')
        <p class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.stock_statuses.in_stock_with_shipping', ['weeks' => $product->shipping_weeks]) }}</p>
    @elseif ($product->isOutOfStock())
        <span class="text-warning fw-medium fs-6">{{ trans('plugins/ecommerce::products.stock_statuses.out_of_stock') }}</span>
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
            @elseif (! $productVariation->with_storehouse_management || $productVariation->quantity < 1)
                <span class="text-success">{{ __('Available') }}</span>
            @elseif ($productVariation->quantity)
                <span class="text-success">
                    @if (EcommerceHelper::showNumberOfProductsInProductSingle())
                        @if ($productVariation->quantity !== 1)
                            {{ __(':number products available', ['number' => $productVariation->quantity]) }}
                        @else
                            {{ __(':number product available', ['number' => $productVariation->quantity]) }}
                        @endif
                    @else
                        {{ __('In stock') }}
                    @endif
                </span>
           @endif
       @endif
    @endif
</div>
