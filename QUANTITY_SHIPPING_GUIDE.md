# Quantity-Based Shipping Rules Implementation

This implementation adds dynamic shipping cost calculation based on the total quantity of items in an order. The shipping fees automatically adjust as customers add or remove items from their cart.

## 🚀 Features

### ✅ **Core Functionality**
- **Dynamic Shipping Calculation**: Shipping costs automatically update based on total item quantity
- **Tiered Pricing Structure**: Different shipping rates for different quantity ranges
- **Real-time Updates**: Shipping costs update instantly as cart quantities change
- **Smart Recommendations**: Suggests optimal quantities for better shipping rates
- **Admin Management**: Full admin panel integration for managing quantity-based rules

### ✅ **Business Benefits**
- **Encourage Bulk Orders**: Lower per-item shipping costs for larger orders
- **Increase Average Order Value**: Customers add more items to reach better shipping tiers
- **Flexible Pricing**: Different rules for different shipping regions
- **Free Shipping Incentives**: Offer free shipping for large quantity orders

## 📋 Implementation Details

### **1. Database Structure**
The implementation extends the existing `ShippingRule` model with a new rule type:
- **Rule Type**: `BASED_ON_QUANTITY`
- **Fields**: `from` (minimum quantity), `to` (maximum quantity), `price` (shipping fee)

### **2. Sample Shipping Rules Created**
The migration automatically creates these sample rules:

| Quantity Range | Shipping Fee | Description |
|----------------|--------------|-------------|
| 1-5 items      | $15.00       | Small Order |
| 6-15 items     | $25.00       | Medium Order |
| 16-30 items    | $35.00       | Large Order |
| 31-49 items    | $50.00       | Bulk Order |
| 50+ items      | **FREE**     | Free Shipping |

### **3. Key Components**

#### **A. Enhanced Enum (`ShippingRuleTypeEnum`)**
```php
public const BASED_ON_QUANTITY = 'based_on_quantity';
```

#### **B. Shipping Fee Service (`HandleShippingFeeService`)**
- Calculates total quantity from cart items
- Applies quantity-based rules during shipping calculation
- Integrates seamlessly with existing price/weight-based rules

#### **C. Quantity Shipping Service (`QuantityBasedShippingService`)**
- Dedicated service for quantity-based calculations
- Provides utility methods for recommendations and breakdowns
- Handles quantity range formatting and display

#### **D. AJAX Controller (`QuantityShippingController`)**
- Real-time shipping calculations via AJAX
- Returns formatted shipping information
- Provides shipping recommendations

#### **E. Frontend Components**
- **Blade Template**: `quantity-shipping-info.blade.php`
- **JavaScript**: `quantity-shipping.js`
- **AJAX Endpoints**: Dynamic shipping updates

## 🛠️ Usage Instructions

### **For Administrators**

1. **Access Shipping Settings**:
   - Go to `Admin Panel → Ecommerce → Settings → Shipping`
   - Navigate to "Shipping Rules" section

2. **Create Quantity-Based Rules**:
   - Click "Add shipping rule"
   - Select "Based on order's total quantity (items)" as rule type
   - Set quantity range (From/To values)
   - Set shipping price
   - Save the rule

3. **Manage Existing Rules**:
   - Edit quantity ranges and prices as needed
   - Enable/disable specific rules
   - Create region-specific quantity rules

### **For Developers**

#### **Include Shipping Info in Templates**
```blade
@include('plugins/ecommerce::themes.includes.quantity-shipping-info')
```

#### **Access Quantity Shipping Service**
```php
$quantityService = app(QuantityBasedShippingService::class);
$totalQuantity = $quantityService->calculateTotalQuantity($cartItems);
$shippingFee = $quantityService->calculateShippingFee($totalQuantity);
$recommendation = $quantityService->getRecommendedQuantity($totalQuantity);
```

#### **JavaScript Integration**
```javascript
// Access the global quantity shipping calculator
window.quantityShippingCalculator.refresh();

// Listen for shipping updates
$(document).on('quantity-shipping:updated', function(event, shippingInfo) {
    console.log('Shipping updated:', shippingInfo);
});
```

### **For Frontend Integration**

#### **Display Shipping Information**
The quantity shipping info component automatically displays:
- Current total quantity
- Current shipping fee
- Shipping rate breakdown table
- Recommendations for better rates

#### **AJAX Endpoints**
- `POST /ajax/calculate-quantity-shipping` - Calculate shipping for given quantities
- `GET /ajax/quantity-shipping-rates` - Get all quantity-based shipping rates
- `POST /ajax/quantity-shipping-recommendation` - Get recommendations for quantity

## 🎯 Business Logic

### **How It Works**
1. **Quantity Calculation**: System counts total items in cart
2. **Rule Matching**: Finds applicable shipping rules for the quantity
3. **Fee Calculation**: Applies the best (lowest) shipping rate
4. **Real-time Updates**: Updates as cart contents change
5. **Recommendations**: Suggests quantity adjustments for savings

### **Rule Priority**
- Quantity-based rules work alongside existing price/weight rules
- System selects the most applicable rule based on order characteristics
- Multiple rule types can be active simultaneously

### **Recommendation Engine**
- Analyzes current quantity vs. available shipping tiers
- Calculates potential savings from reaching next tier
- Provides actionable suggestions to customers

## 🔧 Customization Options

### **Modify Shipping Tiers**
Edit the migration file or create new rules via admin panel:
```php
[
    'name' => 'Custom Tier (20-40 items)',
    'type' => ShippingRuleTypeEnum::BASED_ON_QUANTITY,
    'from' => 20,
    'to' => 40,
    'price' => 30.00,
]
```

### **Customize Display Templates**
Modify `quantity-shipping-info.blade.php` to match your theme:
- Change styling and layout
- Add/remove information sections
- Customize recommendation messages

### **Extend JavaScript Functionality**
Enhance `quantity-shipping.js` for custom behaviors:
- Add custom event handlers
- Integrate with other cart features
- Customize update triggers

## 📊 Benefits for E-commerce

### **Customer Experience**
- **Transparency**: Clear shipping cost breakdown
- **Incentivization**: Encourages larger orders for savings
- **Real-time Feedback**: Immediate cost updates
- **Smart Suggestions**: Helpful recommendations for savings

### **Business Impact**
- **Higher AOV**: Customers add items to reach better shipping tiers
- **Reduced Cart Abandonment**: Clear shipping costs upfront
- **Operational Efficiency**: Automated shipping calculations
- **Competitive Advantage**: Flexible, customer-friendly shipping

## 🚦 Testing

### **Test Scenarios**
1. **Add items to cart** - Verify shipping updates in real-time
2. **Change quantities** - Confirm shipping tier changes
3. **Remove items** - Test shipping recalculation
4. **Cross shipping tiers** - Verify recommendations appear
5. **Admin rule changes** - Test frontend updates

### **Verification Points**
- ✅ Shipping costs update automatically
- ✅ Recommendations appear when beneficial
- ✅ Quantity ranges display correctly
- ✅ Free shipping triggers at correct quantities
- ✅ AJAX requests work properly

## 🔄 Migration and Rollback

### **Apply Changes**
```bash
php artisan migrate
```

### **Rollback (if needed)**
```bash
php artisan migrate:rollback
```

This will remove all quantity-based shipping rules while preserving other shipping configurations.

## 📞 Support

This implementation provides a complete quantity-based shipping solution that integrates seamlessly with the existing Botble ecommerce platform. The system is designed to be:

- **Scalable**: Handles any number of quantity tiers
- **Flexible**: Supports complex shipping scenarios
- **User-friendly**: Clear interface for both admins and customers
- **Performance-optimized**: Efficient calculations and caching

The feature encourages customers to purchase more items while providing transparent, fair shipping costs that benefit both the business and customers. 