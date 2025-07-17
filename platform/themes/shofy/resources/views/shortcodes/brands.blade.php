@if($brands)
    <div class="brands-section style-{{ $style }}">
        <div class="container">
            <div class="row g-4">
                @foreach($brands as $brand)
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="brand-item">
                            <img src="{{ RvMedia::getImageUrl($brand['image']) }}" alt="{{ $brand['name'] }}" class="img-fluid">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .brands-section {
            padding: 40px 0;
        }
        .brand-item {
            text-align: center;
            padding: 15px;
            transition: all 0.3s ease;
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
    </style>
@endif 