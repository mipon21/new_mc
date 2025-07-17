<?php

return [
    'name' => 'Product Inventory',
    'storehouse_management' => 'Storehouse Management',

    'import' => [
        'name' => 'Update Product Inventory',
        'description' => 'Update product inventory in bulk by uploading a CSV/Excel file.',
        'done_message' => 'Updated :count product(s) successfully.',
        'rules' => [
            'id' => 'The ID field is mandatory and must be exists in products table.',
            'name' => 'The name field is mandatory and must be a string.',
            'sku' => 'The SKU field must be a string.',
            'with_storehouse_management' => 'The with storehouse management field must be "Yes" or "No".',
            'quantity' => 'The quantity field is mandatory when with storehouse management is "Yes".',
            'stock_status' => 'The stock status field is mandatory when with storehouse management is "No" and must be one of the following values: :statuses.',
            'shipping_weeks' => 'The shipping weeks field is mandatory when stock status is "in_stock_with_shipping" and must be a string (e.g., "2-4" or "2").',
        ],
    ],

    'export' => [
        'description' => 'Export product inventory to a CSV/Excel file.',
    ],
];
