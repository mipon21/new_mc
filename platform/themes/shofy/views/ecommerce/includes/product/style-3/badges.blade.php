<div class="tp-product-badge-3">
    @if ($product->isOutOfStock())
        <span class="product-out-stock">{{ trans('plugins/ecommerce::products.stock_statuses.out_of_stock') }}</span>
    @else
        @if ($product->productLabels->isNotEmpty())
            @foreach ($product->productLabels as $label)
                <span {!! $label->css_styles !!}>{{ $label->name }}</span>
            @endforeach
        @else
            @if ($product->front_sale_price !== $product->price)
                <span class="product-sale">{{ get_sale_percentage($product->price, $product->front_sale_price) }}</span>
            @endif
        @endif
    @endif
</div>
