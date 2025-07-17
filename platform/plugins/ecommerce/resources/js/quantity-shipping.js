/**
 * Quantity-based Shipping Calculator
 * Handles dynamic shipping fee updates based on cart quantity changes
 */

class QuantityShippingCalculator {
    constructor() {
        this.apiEndpoint = '/ajax/calculate-quantity-shipping';
        this.debounceTimer = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateShippingInfo();
    }

    bindEvents() {
        // Listen for quantity changes in cart
        $(document).on('change', '.qty-input, .quantity-input', (e) => {
            this.debounceUpdate();
        });

        // Listen for add to cart events
        $(document).on('click', '.add-to-cart-button', (e) => {
            setTimeout(() => this.debounceUpdate(), 1000);
        });

        // Listen for remove from cart events
        $(document).on('click', '.remove-cart-item', (e) => {
            setTimeout(() => this.debounceUpdate(), 500);
        });

        // Listen for cart updates
        $(document).on('cart:updated', () => {
            this.debounceUpdate();
        });
    }

    debounceUpdate() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            this.updateShippingInfo();
        }, 300);
    }

    async updateShippingInfo() {
        try {
            const cartData = await this.getCartData();
            const shippingInfo = await this.calculateShipping(cartData);
            this.updateUI(shippingInfo);
        } catch (error) {
            console.error('Error updating quantity shipping info:', error);
        }
    }

    async getCartData() {
        // Get cart data from existing cart instance or API
        const cartItems = [];

        $('.cart-item, .product-item').each(function () {
            const $item = $(this);
            const qty = parseInt($item.find('.qty-input, .quantity-input').val()) || 1;
            const productId = $item.data('product-id') || $item.data('id');

            if (productId) {
                cartItems.push({
                    id: productId,
                    qty: qty
                });
            }
        });

        return {
            items: cartItems,
            totalQuantity: cartItems.reduce((sum, item) => sum + item.qty, 0)
        };
    }

    async calculateShipping(cartData) {
        const response = await fetch(this.apiEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(cartData)
        });

        if (!response.ok) {
            throw new Error('Failed to calculate shipping');
        }

        return await response.json();
    }

    updateUI(shippingInfo) {
        // Update quantity display
        $('.total-quantity').text(shippingInfo.totalQuantity);

        // Update shipping fee
        $('.shipping-fee-amount').text(shippingInfo.formattedFee);
        $('.shipping-price-text').text(shippingInfo.formattedFee);

        // Update recommendation
        this.updateRecommendation(shippingInfo.recommendation);

        // Update shipping rates table
        this.updateRatesTable(shippingInfo.breakdown);

        // Trigger custom event
        $(document).trigger('quantity-shipping:updated', [shippingInfo]);
    }

    updateRecommendation(recommendation) {
        const $recommendationContainer = $('.shipping-recommendation');

        if (recommendation) {
            $recommendationContainer.show();
            $recommendationContainer.find('.recommendation-message').text(recommendation.message);
            $recommendationContainer.find('.current-fee').text(recommendation.currentFeeFormatted);
            $recommendationContainer.find('.new-fee').text(recommendation.newFeeFormatted);
            $recommendationContainer.find('.savings').text(recommendation.savingsFormatted);
        } else {
            $recommendationContainer.hide();
        }
    }

    updateRatesTable(breakdown) {
        const $tbody = $('.shipping-rates-table tbody');
        $tbody.empty();

        breakdown.forEach(item => {
            const $row = $(`
                <tr class="${item.isActive ? 'table-success' : ''}">
                    <td>${item.range}</td>
                    <td>
                        <strong>${item.fee}</strong>
                        ${item.isFree ? '<span class="badge bg-success ms-1">FREE</span>' : ''}
                    </td>
                </tr>
            `);
            $tbody.append($row);
        });
    }

    // Public method to manually trigger update
    refresh() {
        this.updateShippingInfo();
    }

    // Get current shipping info without updating UI
    async getCurrentShippingInfo() {
        const cartData = await this.getCartData();
        return await this.calculateShipping(cartData);
    }
}

// Initialize when DOM is ready
$(document).ready(function () {
    if (typeof window.quantityShippingCalculator === 'undefined') {
        window.quantityShippingCalculator = new QuantityShippingCalculator();
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = QuantityShippingCalculator;
} 