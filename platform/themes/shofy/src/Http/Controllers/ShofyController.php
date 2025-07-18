<?php

namespace Theme\Shofy\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Services\Products\ProductCrossSalePriceService;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Http\Controllers\PublicController;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ShofyController extends PublicController
{
    public function ajaxGetProducts(Request $request): BaseHttpResponse
    {
        try {
            $params = [
                'take' => $limit = $request->integer('limit', 10),
                'random_sort' => true,
                'force_fresh' => true,
                // Add a unique identifier to force fresh query
                'query_id' => uniqid('ajax_random_', true),
            ];

            $type = $request->query('type');
            
            $products = match ($type) {
                'featured' => get_featured_products($params),
                'on-sale' => get_products_on_sale($params),
                'trending' => get_trending_products($params),
                'top-rated' => get_top_rated_products($limit),
                default => get_products($params + EcommerceHelper::withReviewsParams()),
            };
            
            if (empty($products)) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage('No products found');
            }

            // Convert to collection if needed and shuffle again for extra randomness
            if (! $products instanceof Collection) {
                $products = collect($products instanceof Product ? [$products] : $products);
            }
            
            // Shuffle the collection for additional randomness
            if ($products->count() > 1) {
                $products = $products->shuffle();
            }

            return $this
                ->httpResponse()
                ->setData([
                    'count' => number_format($products->count()),
                    'html' => view(
                        Theme::getThemeNamespace('views.ecommerce.includes.product-items'),
                        ['products' => $products, 'itemsPerRow' => get_products_per_row(), 'layout' => 'grid']
                    )->render(),
                ]);
                
        } catch (\Exception $e) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage('Error loading products: ' . $e->getMessage());
        }
    }

    public function ajaxGetCartContent()
    {
        return $this
            ->httpResponse()
            ->setData([
                'content' => Theme::partial('mini-cart.content'),
                'footer' => Theme::partial('mini-cart.footer'),
            ]);
    }

    public function ajaxGetCrossSaleProducts(Product $product, ProductCrossSalePriceService $productCrossSalePriceService)
    {
        $parentProduct = $product;
        $products = $product->crossSaleProducts;

        $productCrossSalePriceService->applyProduct($product);

        return $this
            ->httpResponse()
            ->setData(view(
                Theme::getThemeNamespace(
                    'views.ecommerce.includes.cross-sale-products'
                ),
                compact('products', 'parentProduct')
            )->render());
    }

    public function ajaxGetRelatedProducts(Product $product)
    {
        return $this
            ->httpResponse()
            ->setData(view(
                Theme::getThemeNamespace(
                    'views.ecommerce.includes.related-products'
                ),
                ['products' => get_related_products($product)]
            )->render());
    }
}
