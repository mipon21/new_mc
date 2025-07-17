<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Http\Controllers\QuantityShippingController;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\ProductCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::prefix('ajax')->name('admin.ajax.')->group(function (): void {
        Route::get('search-products', function () {
            $products = Product::query()
                ->wherePublished()
                ->where('is_variation', false)
                ->when(request()->input('search'), function (Builder $query, string $search): void {
                    $query->where('name', 'like', "%$search%");
                })
                ->select('name', 'id')
                ->paginate();

            return BaseHttpResponse::make()
                ->setData($products);
        })->name('search-products');

        Route::get('search-categories', function () {
            $categories = ProductCategory::query()
                ->when(request()->input('search'), function (Builder $query, string $search): void {
                    $query->where('name', 'like', "%$search%");
                })
                ->select('name', 'id')
                ->paginate();

            return BaseHttpResponse::make()
                ->setData($categories);
        })->name('search-categories');

        Route::get('search-collections', function () {
            $collections = ProductCollection::query()
                ->when(request()->input('search'), function (Builder $query, string $search): void {
                    $query->where('name', 'like', "%$search%");
                })
                ->select('name', 'id')
                ->paginate();

            return BaseHttpResponse::make()
                ->setData($collections);
        })->name('search-collections');

        // Quantity-based shipping routes
        Route::post('calculate-quantity-shipping', [QuantityShippingController::class, 'calculateShipping'])
            ->name('calculate-quantity-shipping');
        
        Route::get('quantity-shipping-rates', [QuantityShippingController::class, 'getShippingRates'])
            ->name('quantity-shipping-rates');
        
        Route::post('quantity-shipping-recommendation', [QuantityShippingController::class, 'getRecommendation'])
            ->name('quantity-shipping-recommendation');
    });
});

// Public AJAX routes for frontend
Route::prefix('ajax')->name('public.ajax.')->group(function (): void {
    Route::post('calculate-quantity-shipping', [QuantityShippingController::class, 'calculateShipping'])
        ->name('calculate-quantity-shipping');
    
    Route::get('quantity-shipping-rates', [QuantityShippingController::class, 'getShippingRates'])
        ->name('quantity-shipping-rates');
    
    Route::post('quantity-shipping-recommendation', [QuantityShippingController::class, 'getRecommendation'])
        ->name('quantity-shipping-recommendation');
});
