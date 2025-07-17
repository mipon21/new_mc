<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Botble\Ecommerce\Models\Shipping;
use Botble\Ecommerce\Models\ShippingRule;
use Botble\Ecommerce\Enums\ShippingRuleTypeEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create sample quantity-based shipping rules
        $this->createQuantityBasedShippingRules();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove quantity-based shipping rules
        ShippingRule::where('type', ShippingRuleTypeEnum::BASED_ON_QUANTITY)->delete();
    }

    /**
     * Create sample quantity-based shipping rules
     */
    private function createQuantityBasedShippingRules(): void
    {
        // Get or create default shipping region
        $defaultShipping = Shipping::whereNull('country')->first();

        if (!$defaultShipping) {
            $defaultShipping = Shipping::create([
                'title' => 'All',
                'country' => null,
            ]);
        }

        // Create quantity-based shipping rules
        $quantityRules = [
            [
                'name' => 'Small Order (1-5 items)',
                'type' => ShippingRuleTypeEnum::BASED_ON_QUANTITY,
                'from' => 1,
                'to' => 5,
                'price' => 15.00,
                'shipping_id' => $defaultShipping->id,
            ],
            [
                'name' => 'Medium Order (6-15 items)',
                'type' => ShippingRuleTypeEnum::BASED_ON_QUANTITY,
                'from' => 6,
                'to' => 15,
                'price' => 25.00,
                'shipping_id' => $defaultShipping->id,
            ],
            [
                'name' => 'Large Order (16-30 items)',
                'type' => ShippingRuleTypeEnum::BASED_ON_QUANTITY,
                'from' => 16,
                'to' => 30,
                'price' => 35.00,
                'shipping_id' => $defaultShipping->id,
            ],
            [
                'name' => 'Bulk Order (31+ items)',
                'type' => ShippingRuleTypeEnum::BASED_ON_QUANTITY,
                'from' => 31,
                'to' => null, // No upper limit
                'price' => 50.00,
                'shipping_id' => $defaultShipping->id,
            ],
            [
                'name' => 'Free Shipping for Large Quantities (50+ items)',
                'type' => ShippingRuleTypeEnum::BASED_ON_QUANTITY,
                'from' => 50,
                'to' => null,
                'price' => 0.00, // Free shipping
                'shipping_id' => $defaultShipping->id,
            ],
        ];

        foreach ($quantityRules as $ruleData) {
            // Check if rule already exists to avoid duplicates
            $existingRule = ShippingRule::where([
                'name' => $ruleData['name'],
                'type' => $ruleData['type'],
                'shipping_id' => $ruleData['shipping_id'],
            ])->first();

            if (!$existingRule) {
                ShippingRule::create($ruleData);
            }
        }
    }
};
