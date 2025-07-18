@if($brands)
    <section class="brands-section style-{{ $style ?? 'style-1' }} pt-50 pb-50">
        <div class="container">
            <div class="row align-items-center mb-40">
                <div class="col-xl-4 col-md-6">
                    @php
                        $shortcode = (object) [
                            'title' => $title ?? '',
                            'subtitle' => $subtitle ?? '',
                        ];
                    @endphp
                    {!! Theme::partial('section-title', compact('shortcode')) !!}
                </div>
                <div class="col-xl-8 col-md-6">
                    @if(isset($buttonLabel) && $buttonLabel && isset($buttonUrl) && $buttonUrl)
                        <div class="tp-blog-more-wrapper d-flex justify-content-md-end">
                            <div class="tp-blog-more text-md-end">
                                <a href="{{ $buttonUrl }}" class="tp-btn tp-btn-2 tp-btn-blue">
                                    {{ $buttonLabel }}
                                    <svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 6.99976L1 6.99976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M9.9502 0.975414L16.0002 6.99941L9.9502 13.0244" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                                <span class="tp-blog-more-border"></span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="brands-wrapper">
                <div class="brands-scroll">
                    <div class="brands-track">
                        @foreach($brands as $brand)
                            <div class="brand-item">
                                @if(!empty($brand['url']))
                                    <a href="{{ $brand['url'] }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ RvMedia::getImageUrl($brand['image']) }}" alt="{{ $brand['name'] }}" class="img-fluid">
                                    </a>
                                @else
                                    <img src="{{ RvMedia::getImageUrl($brand['image']) }}" alt="{{ $brand['name'] }}" class="img-fluid">
                                @endif
                            </div>
                        @endforeach
                        @foreach($brands as $brand)
                            <div class="brand-item">
                                @if(!empty($brand['url']))
                                    <a href="{{ $brand['url'] }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ RvMedia::getImageUrl($brand['image']) }}" alt="{{ $brand['name'] }}" class="img-fluid">
                                    </a>
                                @else
                                    <img src="{{ RvMedia::getImageUrl($brand['image']) }}" alt="{{ $brand['name'] }}" class="img-fluid">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .brands-section {
            overflow: hidden;
        }
        .brands-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
        }
        .brands-scroll {
            width: 100%;
            overflow: hidden;
            position: relative;
        }
        .brands-track {
            display: flex;
            gap: 30px;
            animation: scroll 30s linear infinite;
            width: max-content;
        }
        .brands-track:hover {
            animation-play-state: paused;
        }
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }
        .brand-item {
            flex: 0 0 auto;
            text-align: center;
            padding: 15px;
            transition: all 0.3s ease;
            min-width: 150px;
        }
        .brand-item:hover {
            transform: translateY(-5px);
        }
        .brand-item img {
            max-width: 100%;
            height: auto;
            filter: grayscale(100%);
            transition: all 0.3s ease;
        }
        .brand-item:hover img {
            filter: grayscale(0%);
        }
        .brand-item a {
            display: block;
            text-decoration: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.querySelector('.brands-scroll');
            let isDown = false;
            let startX;
            let scrollLeft;

            scrollContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                scrollContainer.style.cursor = 'grabbing';
                startX = e.pageX - scrollContainer.offsetLeft;
                scrollLeft = scrollContainer.scrollLeft;
            });

            scrollContainer.addEventListener('mouseleave', () => {
                isDown = false;
                scrollContainer.style.cursor = 'grab';
            });

            scrollContainer.addEventListener('mouseup', () => {
                isDown = false;
                scrollContainer.style.cursor = 'grab';
            });

            scrollContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - scrollContainer.offsetLeft;
                const walk = (x - startX) * 2;
                scrollContainer.scrollLeft = scrollLeft - walk;
            });
        });
    </script>
@endif 