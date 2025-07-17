<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Enums\ShippingRuleTypeEnum;
use Botble\Ecommerce\Models\ShippingRule;
use Illuminate\Support\Collection;

class QuantityBasedShippingService
{
    /**
     * Calculate total quantity from cart items or products
     */
    public function calculateTotalQuantity(array $items): int
    {
        $totalQuantity = 0;

        foreach ($items as $item) {
            if (is_array($item) && isset($item['qty'])) {
                $totalQuantity += (int) $item['qty'];
            } elseif (is_object($item) && property_exists($item, 'qty')) {
                $totalQuantity += (int) $item->qty;
            } elseif (is_object($item) && method_exists($item, 'getQuantity')) {
                $totalQuantity += (int) $item->getQuantity();
            }
        }

        return $totalQuantity;
    }

    /**
     * Get applicable quantity-based shipping rules for a given quantity
     */
    public function getApplicableRules(int $totalQuantity, ?int $shippingId = null): Collection
    {
        $query = ShippingRule::where('type', ShippingRuleTypeEnum::BASED_ON_QUANTITY)
            ->where('from', '<=', $totalQuantity)
            ->where(function ($q) use ($totalQuantity) {
                $q->whereNull('to')
                    ->orWhere('to', '>=', $totalQuantity);
            });

        if ($shippingId) {
            $query->where('shipping_id', $shippingId);
        }

        return $query->orderBy('price')->get();
    }

    /**
     * Get the best (cheapest) shipping rule for a given quantity
     */
    public function getBestRule(int $totalQuantity, ?int $shippingId = null): ?ShippingRule
    {
        return $this->getApplicableRules($totalQuantity, $shippingId)->first();
    }

    /**
     * Calculate shipping fee based on quantity
     */
    public function calculateShippingFee(int $totalQuantity, ?int $shippingId = null): float
    {
        $rule = $this->getBestRule($totalQuantity, $shippingId);

        return $rule ? (float) $rule->price : 0.0;
    }

    /**
     * Get quantity ranges for display in admin or frontend
     */
    public function getQuantityRanges(?int $shippingId = null): array
    {
        $query = ShippingRule::where('type', ShippingRuleTypeEnum::BASED_ON_QUANTITY);

        if ($shippingId) {
            $query->where('shipping_id', $shippingId);
        }

        $rules = $query->orderBy('from')->get();
        $ranges = [];

        foreach ($rules as $rule) {
            $ranges[] = [
                'id' => $rule->id,
                'name' => $rule->name,
                'from' => $rule->from,
                'to' => $rule->to,
                'price' => $rule->price,
                'formatted_price' => format_price($rule->price),
                'range_text' => $this->formatQuantityRange($rule->from, $rule->to),
            ];
        }

        return $ranges;
    }

    /**
     * Format quantity range for display
     */
    public function formatQuantityRange(int $from, ?int $to = null): string
    {
        if ($to === null) {
            return $from . '+ items';
        }

        if ($from === $to) {
            return $from . ' item' . ($from > 1 ? 's' : '');
        }

        return $from . '-' . $to . ' items';
    }

    /**
     * Get shipping fee breakdown for different quantity ranges
     */
    public function getShippingFeeBreakdown(?int $shippingId = null): array
    {
        $ranges = $this->getQuantityRanges($shippingId);
        $breakdown = [];

        foreach ($ranges as $range) {
            $breakdown[] = [
                'range' => $range['range_text'],
                'fee' => $range['formatted_price'],
                'description' => sprintf(
                    'Orders with %s: %s shipping fee',
                    $range['range_text'],
                    $range['formatted_price']
                ),
            ];
        }

        return $breakdown;
    }

    /**
     * Check if quantity-based shipping is enabled
     */
    public function isQuantityBasedShippingEnabled(): bool
    {
        return ShippingRule::where('type', ShippingRuleTypeEnum::BASED_ON_QUANTITY)->exists();
    }

    /**
     * Get recommended quantity for better shipping rates
     */
    public function getRecommendedQuantity(int $currentQuantity, ?int $shippingId = null): ?array
    {
        $currentRule = $this->getBestRule($currentQuantity, $shippingId);

        if (!$currentRule) {
            return null;
        }

        // Find next better rule
        $betterRules = ShippingRule::where('type', ShippingRuleTypeEnum::BASED_ON_QUANTITY)
            ->where('price', '<', $currentRule->price)
            ->where('from', '>', $currentQuantity)
            ->when($shippingId, fn($q) => $q->where('shipping_id', $shippingId))
            ->orderBy('from')
            ->first();

        if ($betterRules) {
            $itemsNeeded = $betterRules->from - $currentQuantity;
            $savings = $currentRule->price - $betterRules->price;

            return [
                'items_needed' => $itemsNeeded,
                'target_quantity' => $betterRules->from,
                'current_fee' => $currentRule->price,
                'new_fee' => $betterRules->price,
                'savings' => $savings,
                'message' => sprintf(
                    'Add %d more item%s to save %s on shipping!',
                    $itemsNeeded,
                    $itemsNeeded > 1 ? 's' : '',
                    format_price($savings)
                ),
            ];
        }

        return null;
    }
}