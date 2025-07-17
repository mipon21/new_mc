@if (
    $order->sub_total != $order->amount
    || $order->shipping_method->getValue()
    || (EcommerceHelper::isTaxEnabled() && ((float) $order->tax_amount || (float) $order->shipping_tax_amount))
    || (float) $order->discount_amount
)
    <hr class="border-dark-subtle" />
@endif

@if ($order->sub_total != $order->amount)
    @include('plugins/ecommerce::orders.thank-you.total-row', [
        'label' => __('Subtotal'),
        'value' => format_price($order->sub_total),
    ])
@endif

@if ($order->shipping_method->getValue())
    @include('plugins/ecommerce::orders.thank-you.total-row', [
        'label' => __('Shipping fee'),
        'value' => $order->shipping_method_name . ' - ' . format_price($order->shipping_amount),
    ])
@endif

@if (EcommerceHelper::isTaxEnabled())
    @if ((float) $order->tax_amount)
        @include('plugins/ecommerce::orders.thank-you.total-row', [
            'label' => __('Product Tax') . ($order->tax_classes_name ? ' (' . $order->tax_classes_name . ')' : ''),
            'value' => format_price($order->tax_amount),
        ])
    @endif
    @if ((float) $order->shipping_tax_amount)
        @include('plugins/ecommerce::orders.thank-you.total-row', [
            'label' => trans('plugins/ecommerce::products.form.shipping_tax') . ' (19%)',
            'value' => format_price($order->shipping_tax_amount),
        ])
    @endif
@endif

@if ((float) $order->discount_amount)
    @include('plugins/ecommerce::orders.thank-you.total-row', [
        'label' => __('Discount'),
        'value' =>
            format_price($order->discount_amount) .
            ($order->coupon_code
                ? ' <small>(' . __('Using coupon code') . ': <strong>' . $order->coupon_code . '</strong>)</small>'
                : ''),
    ])
@endif

@if ((float) $order->payment_fee)
    @include('plugins/ecommerce::orders.thank-you.total-row', [
        'label' => __('plugins/payment::payment.payment_fee'),
        'value' => format_price($order->payment_fee),
    ])
@endif

{!! apply_filters('ecommerce_thank_you_total_info', null, $order) !!}

<hr class="border-dark-subtle" />

<div class="row">
    <div class="col-6">
        <p>{{ __('Total') }}:</p>
    </div>
    <div class="col-6 float-end">
        <p class="total-text raw-total-text"> {{ format_price($order->amount) }} </p>
    </div>
</div>
