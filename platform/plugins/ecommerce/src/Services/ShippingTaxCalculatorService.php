<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Facades\EcommerceHelper;

class ShippingTaxCalculatorService
{
    public function calculate(float $shippingAmount, float $taxRate = 19.0): float
    {
        if (! EcommerceHelper::isTaxEnabled()) {
            return 0;
        }

        return round($shippingAmount * ($taxRate / 100), 2);
    }
} 