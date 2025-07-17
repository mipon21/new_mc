@if($brands)
    <div class="brands-section style-{{ $style }}">
        <div class="container">
            @if($title || $subtitle)
                <div class="section-title mb-5">
                    @if($subtitle)
                        <span class="subtitle">{{ $subtitle }}</span>
                    @endif
                    @if($title)
                        <h2 class="title">{{ $title }}</h2>
                    @endif
                </div>
            @endif
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
    </div>

    <style>
        .brands-section {
            padding: 40px 0;
            overflow: hidden;
        }
        .section-title {
            margin-bottom: 40px;
            text-align: left;
        }
        .section-title .subtitle {
            color: var(--primary-color);
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
        }
        .section-title .title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 0;
            color: var(--heading-color);
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