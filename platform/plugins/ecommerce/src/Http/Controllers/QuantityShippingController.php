<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Services\QuantityBasedShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuantityShippingController extends BaseController
{
    public function __construct(
        protected QuantityBasedShippingService $quantityShippingService
    ) {
    }

    /**
     * Calculate shipping fee based on quantity via AJAX
     */
    public function calculateShipping(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'array',
            'items.*.qty' => 'integer|min:1',
            'totalQuantity' => 'integer|min:0',
        ]);

        $totalQuantity = $request->input('totalQuantity', 0);

        // If totalQuantity is not provided, calculate from items
        if ($totalQuantity === 0 && $request->has('items')) {
            $totalQuantity = collect($request->input('items'))->sum('qty');
        }

        $shippingFee = $this->quantityShippingService->calculateShippingFee($totalQuantity);
        $breakdown = $this->quantityShippingService->getShippingFeeBreakdown();
        $recommendation = $this->quantityShippingService->getRecommendedQuantity($totalQuantity);

        // Mark active range in breakdown
        $breakdown = collect($breakdown)->map(function ($item) use ($totalQuantity) {
            $item['isActive'] = $this->isQuantityInRange($totalQuantity, $item['range']);
            $item['isFree'] = $item['fee'] === format_price(0);
            return $item;
        })->toArray();

        return $this->httpResponse()->setData([
            'totalQuantity' => $totalQuantity,
            'shippingFee' => $shippingFee,
            'formattedFee' => format_price($shippingFee),
            'breakdown' => $breakdown,
            'recommendation' => $recommendation ? [
                'message' => $recommendation['message'],
                'itemsNeeded' => $recommendation['items_needed'],
                'targetQuantity' => $recommendation['target_quantity'],
                'currentFee' => $recommendation['current_fee'],
                'newFee' => $recommendation['new_fee'],
                'savings' => $recommendation['savings'],
                'currentFeeFormatted' => format_price($recommendation['current_fee']),
                'newFeeFormatted' => format_price($recommendation['new_fee']),
                'savingsFormatted' => format_price($recommendation['savings']),
            ] : null,
        ]);
    }

    /**
     * Get quantity-based shipping rates
     */
    public function getShippingRates(Request $request): JsonResponse
    {
        $shippingId = $request->input('shipping_id');
        $ranges = $this->quantityShippingService->getQuantityRanges($shippingId);

        return $this->httpResponse()->setData([
            'ranges' => $ranges,
            'isEnabled' => $this->quantityShippingService->isQuantityBasedShippingEnabled(),
        ]);
    }

    /**
     * Get shipping recommendation for current quantity
     */
    public function getRecommendation(Request $request): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'shipping_id' => 'nullable|integer',
        ]);

        $quantity = $request->input('quantity');
        $shippingId = $request->input('shipping_id');

        $recommendation = $this->quantityShippingService->getRecommendedQuantity($quantity, $shippingId);

        return $this->httpResponse()->setData([
            'recommendation' => $recommendation,
            'hasRecommendation' => $recommendation !== null,
        ]);
    }

    /**
     * Check if quantity is within a range string
     */
    private function isQuantityInRange(int $quantity, string $rangeString): bool
    {
        // Parse range strings like "1-5 items", "6-15 items", "31+ items"
        if (strpos($rangeString, '+') !== false) {
            // Handle "31+ items" format
            $minQuantity = (int) str_replace(['+', ' items', ' item'], '', $rangeString);
            return $quantity >= $minQuantity;
        }

        if (strpos($rangeString, '-') !== false) {
            // Handle "1-5 items" format
            $parts = explode('-', $rangeString);
            $min = (int) $parts[0];
            $max = (int) str_replace([' items', ' item'], '', $parts[1]);
            return $quantity >= $min && $quantity <= $max;
        }

        // Handle single quantity like "1 item"
        $singleQuantity = (int) str_replace([' items', ' item'], '', $rangeString);
        return $quantity === $singleQuantity;
    }
}