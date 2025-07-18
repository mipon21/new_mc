<section class="tp-product-area position-relative pt-30 pb-30">
    <div class="container">
        <div class="row align-items-center mb-40">
            <div @class(['col-xl-5 col-lg-6 col-md-5' => count($selectedTabs) > 1, 'col-12' => count($selectedTabs) <= 1])>
                {!! Theme::partial('section-title', compact('shortcode')) !!}
            </div>
            <div @class(['col-xl-7 col-lg-6 col-md-7' => count($selectedTabs) > 1, 'd-none' => count($selectedTabs) <= 1])>
                <div class="tp-product-tab tp-product-tab-border tp-tab d-flex justify-content-md-end">
                    <ul
                        class="nav nav-tabs justify-content-sm-end"
                        id="productTab"
                        role="tablist"
                        data-ajax-url="{{ route('public.ajax.products', ['limit' => $shortcode->limit ?: 8, '_' => time()]) }}"
                    >
                        @foreach($productTabs as $key => $tab)
                            @continue(! in_array($key, $selectedTabs) || (! EcommerceHelper::isReviewEnabled() && $key === 'top-rated'))

                            <li class="nav-item" role="presentation">
                                <button
                                    @class(['nav-link', 'active' => $loop->first])
                                    id="{{ $key }}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tab-pane"
                                    type="button"
                                    role="tab"
                                    aria-controls="tab-pane"
                                    @if ($loop->first) aria-selected="true" @endif
                                    data-bb-toggle="product-tab"
                                    data-bb-value="{{ $key }}"
                                >
                                    {{ $tab }}
                                    <span class="tp-product-tab-line">
                                        {!! Theme::partial('section-title-shape') !!}
                                    </span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="tp-product-tab-content">
                    <div class="tab-content" id="productTabContent">
                        <div class="tab-pane fade show active" id="tab-pane" role="tabpanel" aria-labelledby="tab" tabindex="0">
                            @if(count($groups) > 0)
                                @foreach($groups as $key => $tab)
                                    @continue(! isset($tab['products']))
                                    @if($tab['products']->count() > 0)
                                        @include(
                                            Theme::getThemeNamespace('views.ecommerce.includes.product-items'),
                                            ['products' => $tab['products'], 'itemsPerRow' => get_products_per_row(), 'layout' => 'grid']
                                        )
                                    @else
                                        <div class="alert alert-info">No products found in {{ $tab['title'] }} category.</div>
                                    @endif
                                @endforeach
                            @else
                                <div class="alert alert-warning">No product groups configured.</div>
                            @endif
                            <div id="ajax-loading-error" class="alert alert-danger mt-3 d-none">
                                There was an error loading products. Please try refreshing the page.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add error handling for AJAX requests
        $(document).ajaxError(function(event, jqxhr, settings, error) {
            console.error("AJAX Error:", error, jqxhr.responseText);
            $('#ajax-loading-error').removeClass('d-none');
        });
    });
</script>
