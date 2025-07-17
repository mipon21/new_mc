@if ($config['image'])
    <div class="col-12">
        <div class="tp-footer-payment text-center">
            <p>
                @if (($url = $config['url']) && $url !== '#')
                    <a href="{{ $url }}">
                        {{ RvMedia::image($config['image'], 'footer image') }}
                    </a>
                @else
                    {{ RvMedia::image($config['image'], 'footer image') }}
                @endif
            </p>
        </div>
    </div>
@endif
