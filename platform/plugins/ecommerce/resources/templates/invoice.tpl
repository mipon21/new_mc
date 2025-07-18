<!doctype html>
<html {{ html_attributes }}>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ 'plugins/ecommerce::order.invoice_for_order'|trans }} {{ invoice.code }}</title>

    {{ settings.font_css }}

    <style>
        body {
            font-size: 15px;
            font-family: '{{ settings.font_family }}', Arial, sans-serif !important;
            position: relative;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table tr td {
            padding: 0;
        }

        table tr td:last-child {
            text-align: right;
        }

        .bold, strong, b, .total, .stamp {
            font-weight: 700;
        }

        .right {
            text-align: right;
        }

        .large {
            font-size: 1.75em;
        }

        .total {
            color: #fb7578;
        }

        .large.total img {
            width: 14px;
        }

        .logo-container {
            margin: 20px 0 50px;
        }

        .invoice-info-container {
            font-size: .875em;
        }

        .invoice-info-container td {
            padding: 4px 0;
        }

        .line-items-container {
            font-size: .875em;
            margin: 70px 0;
        }

        .line-items-container th {
            border-bottom: 2px solid #ddd;
            color: #999;
            font-size: .75em;
            padding: 10px 0 15px;
            text-align: left;
            text-transform: uppercase;
        }

        .line-items-container th:last-child {
            text-align: right;
        }

        .line-items-container td {
            padding: 10px 0;
        }

        .line-items-container tbody tr:first-child td {
            padding-top: 25px;
        }

        .line-items-container.has-bottom-border tbody tr:last-child td {
            border-bottom: 2px solid #ddd;
            padding-bottom: 25px;
        }

        .line-items-container th.heading-quantity {
            width: 50px;
        }

        .line-items-container th.heading-price {
            text-align: right;
            width: 100px;
        }

        .line-items-container th.heading-subtotal {
            width: 100px;
        }

        .payment-info {
            font-size: .875em;
            line-height: 1.5;
            width: 38%
        }

        small {
            font-size: 80%;
        }

        .stamp {
            border: 2px solid #555;
            color: #555;
            display: inline-block;
            font-size: 18px;
            line-height: 1;
            opacity: .5;
            padding: .3rem .75rem;
            position: fixed;
            text-transform: uppercase;
            top: 40%;
            left: 40%;
            transform: rotate(-14deg);
        }

        .is-failed {
            border-color: #d23;
            color: #d23;
        }

        .is-completed {
            border-color: #0a9928;
            color: #0a9928;
        }

        body[dir=rtl] {
            direction: rtl;
        }

        body[dir=rtl] .right {
            text-align: left;
        }

        body[dir=rtl] table tr td:last-child {
            text-align: left;
        }

        body[dir=rtl] .line-items-container th.heading-price {
            text-align: left;
        }

        body[dir=rtl] .line-items-container th:last-child {
            text-align: left;
        }

        body[dir=rtl] .line-items-container th {
            text-align: right;
        }

        {{ settings.extra_css }}
    </style>

    {{ settings.header_html }}

    {{ invoice_header_filter | raw }}
</head>
<body {{ body_attributes }}>

{{ invoice_body_filter | raw }}

{% if (get_ecommerce_setting('enable_invoice_stamp', 1) == 1) %}
    {% if invoice.status == 'canceled' %}
        <div class="stamp is-failed">
            {{ 'plugins/ecommerce::invoice.statuses.canceled'|trans }}
        </div>
    {% elseif (payment_status_label) %}
        <div class="stamp {% if payment_status == 'completed' %} is-completed {% else %} is-failed {% endif %}">
            {{ payment_status_label }}
        </div>
    {% endif %}
{% endif %}

<table class="invoice-info-container">
    <tr>
        <td>
            <div class="logo-container">
                {% if logo %}
                    <img src="{{ logo_full_path }}" style="width:100%; max-width:150px;" alt="site_title">
                {% endif %}
            </div>
        </td>
        <td>
            {% if invoice.created_at %}
                <p>
                    <strong>{{ invoice.created_at|date(settings.date_format) }}</strong>
                </p>
            {% endif %}
            <p>
                <strong style="display: inline-block">{{ 'plugins/ecommerce::order.invoice'|trans }}: </strong>
                <span style="display: inline-block">{{ invoice.code }}</span>
            </p>
            <p>
                <strong style="display: inline-block">{{ 'plugins/ecommerce::order.order_id'|trans }}: </strong>
                <span style="display: inline-block">{{ invoice.reference.code }}</span>
            </p>
        </td>
    </tr>
</table>

<table class="invoice-info-container">
    <tr>
        <td>
            {% if company_name %}
                <p>{{ company_name }}</p>
            {% endif %}

            {% if company_address %}
                <p>{{ company_address }}</p>
            {% endif %}

            {% if company_phone %}
                <p>{{ company_phone }}</p>
            {% endif %}

            {% if company_email %}
                <p>{{ company_email }}</p>
            {% endif %}

            {% if company_tax_id %}
                <p>{{ 'plugins/ecommerce::invoice.detail.tax_id'|trans }}: {{ company_tax_id }}</p>
            {% endif %}
        </td>
        <td>
            {% if invoice.customer_name %}
                <p>{{ invoice.customer_name }}</p>
            {% endif %}
            {% if invoice.customer_email %}
                <p>{{ invoice.customer_email }}</p>
            {% endif %}
            {% if invoice.customer_address %}
                <p>{{ invoice.customer_address }}</p>
            {% endif %}
            {% if invoice.customer_phone %}
                <p>{{ invoice.customer_phone }}</p>
            {% endif %}
            {% if invoice.customer_tax_id %}
                <p>{{ 'plugins/ecommerce::invoice.detail.tax_id'|trans }}: {{ invoice.customer_tax_id }}</p>
            {% endif %}
        </td>
    </tr>
</table>

{% if invoice.description %}
    <table class="invoice-info-container">
        <tr style="text-align: left">
            <td style="text-align: left">
                <p>{{ 'plugins/ecommerce::order.note'|trans }}: {{ invoice.description }}</p>
            </td>
        </tr>
    </table>
{% endif %}

<table class="line-items-container">
    <thead>
    <tr>
        <th class="heading-description">{{ 'plugins/ecommerce::products.form.product'|trans }}</th>
        <th class="heading-description">{{ 'plugins/ecommerce::products.form.options'|trans }}</th>
        <th class="heading-quantity">{{ 'plugins/ecommerce::products.form.quantity'|trans }}</th>
        <th class="heading-price">{{ 'plugins/ecommerce::products.form.price'|trans }}</th>
        <th class="heading-subtotal">{{ 'plugins/ecommerce::products.form.total'|trans }}</th>
    </tr>
    </thead>
    <tbody>
    {% for item in invoice.items %}
        <tr>
            <td>{{ item.name }} {% if item.options.sku %} ({{ item.options.sku }}) {% endif %}</td>
            <td>
                {% if item.options %}
                    {% if item.options.attributes %}
                        <div><small>{{ 'plugins/ecommerce::invoice.detail.attributes'|trans }}: {{ item.options.attributes }}</small></div>
                    {% endif %}
                    {% if item.options.product_options %}
                        <div><small>{{ 'plugins/ecommerce::invoice.detail.product_options'|trans }}: {{ item.options.product_options }}</small></div>
                    {% endif %}
                    {% if item.options.license_code %}
                        <div><small>{{ 'plugins/ecommerce::invoice.detail.license_code'|trans }}: {{ item.options.license_code }}</small></div>
                    {% endif %}
                {% endif %}
            </td>
            <td>{{ item.qty }}</td>
            <td class="right">{{ item.price|price_format }}</td>
            <td class="bold">{{ item.sub_total|price_format }}</td>
        </tr>
    {% endfor %}

    <tr>
        <td colspan="4" class="right">
            {{ 'plugins/ecommerce::invoice.detail.quantity'|trans }}
        </td>
        <td class="bold">
            {{ total_quantity|number_format }}
        </td>
    </tr>

    <tr>
        <td colspan="4" class="right">
            {{ 'plugins/ecommerce::products.form.sub_total'|trans }}
        </td>
        <td class="bold">
            {{ invoice.sub_total|price_format }}
        </td>
    </tr>

    {% if invoice.tax_amount > 0 %}
        <tr>
            <td colspan="4" class="right">
                {{ 'plugins/ecommerce::products.form.tax'|trans }} <small>({{ tax_classes_name }})</small>
            </td>
            <td class="bold">
                {{ invoice.tax_amount|price_format }}
            </td>
        </tr>
    {% endif %}

    {% if invoice.payment_fee > 0 %}
        <tr>
            <td colspan="4" class="right">
                {{ 'plugins/payment::payment.payment_fee'|trans }}
            </td>
            <td class="bold">
                {{ invoice.payment_fee|price_format }}
            </td>
        </tr>
    {% endif %}

    {% if invoice.shipping_amount > 0 %}
        <tr>
            <td colspan="4" class="right">
                {{ 'plugins/ecommerce::products.form.shipping_fee'|trans }}
            </td>
            <td class="bold">
                {{ invoice.shipping_amount|price_format }}
            </td>
        </tr>
    {% endif %}

    {% if invoice.shipping_tax_amount > 0 %}
        <tr>
            <td colspan="4" class="right">
                {{ 'plugins/ecommerce::products.form.shipping_tax'|trans }} (19%)
            </td>
            <td class="bold">
                {{ invoice.shipping_tax_amount|price_format }}
            </td>
        </tr>
    {% endif %}

    {% if invoice.discount_amount > 0 %}
        <tr>
            <td colspan="4" class="right">
                {{ 'plugins/ecommerce::products.form.discount'|trans }}
            </td>
            <td class="bold">
                {{ invoice.discount_amount|price_format }}
            </td>
        </tr>
    {% endif %}

    @if((float)$invoice->tax_amount)
        <tr>
            <td colspan="4" class="text-end">{{ trans('plugins/ecommerce::products.form.product_tax') }}</td>
            <td class="text-end">{{ format_price($invoice->tax_amount) }}</td>
        </tr>
    @endif

    @if((float)$invoice->shipping_amount)
        <tr>
            <td colspan="4" class="text-end">{{ trans('plugins/ecommerce::products.form.shipping_fee') }}</td>
            <td class="text-end">{{ format_price($invoice->shipping_amount) }}</td>
        </tr>
    @endif

    @if((float)$invoice->shipping_tax_amount)
        <tr>
            <td colspan="4" class="text-end">{{ trans('plugins/ecommerce::products.form.shipping_tax') }} (19%)</td>
            <td class="text-end">{{ format_price($invoice->shipping_tax_amount) }}</td>
        </tr>
    @endif
    </tbody>
</table>

<table class="line-items-container">
    <thead>
    <tr>
        <th>{{ 'plugins/ecommerce::order.payment_info'|trans }}</th>
        <th>{{ 'plugins/ecommerce::order.total_amount'|trans }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="payment-info">
            {% if payment_method %}
                <div>
                    {{ 'plugins/ecommerce::order.payment_method'|trans }}: <strong>{{ payment_method }}</strong>
                </div>
            {% endif %}

            {% if payment_status %}
                <div>
                    {{ 'plugins/ecommerce::order.payment_status_label'|trans }}: <strong>{{ payment_status_label }}</strong>
                </div>
            {% endif %}

            {% if payment_description %}
                <div>
                    {{ 'plugins/ecommerce::order.payment_info'|trans }}: <strong>{{ payment_description | raw }}</strong>
                </div>
            {% endif %}

            {{ invoice_payment_info_filter | raw }}
        </td>
        <td class="large total"><p>{{ invoice.amount|price_format }}</p></td>
    </tr>
    </tbody>
</table>
{{ ecommerce_invoice_footer | raw }}
</body>
</html>
