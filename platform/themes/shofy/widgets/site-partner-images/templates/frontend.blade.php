<div class="col-12">
    <div class="tp-footer-partners text-center" style="max-width: 430px; margin: 0 auto;">
        <div class="row justify-content-center g-2" style="height: 110px; align-items: center;">
            @if ($config['image1'])
                <div class="col-auto">
                    <div class="partner-image">
                        @if (($url = $config['url1']) && $url !== '#')
                            <a href="{{ $url }}" target="_blank">
                                {{ RvMedia::image($config['image1'], 'partner image 1', null, true, ['class' => 'img-fluid', 'style' => 'max-width: 130px; max-height: 90px; width: auto; height: auto; object-fit: contain;']) }}
                            </a>
                        @else
                            {{ RvMedia::image($config['image1'], 'partner image 1', null, true, ['class' => 'img-fluid', 'style' => 'max-width: 130px; max-height: 90px; width: auto; height: auto; object-fit: contain;']) }}
                        @endif
                    </div>
                </div>
            @endif
            
            @if ($config['image2'])
                <div class="col-auto">
                    <div class="partner-image">
                        @if (($url = $config['url2']) && $url !== '#')
                            <a href="{{ $url }}" target="_blank">
                                {{ RvMedia::image($config['image2'], 'partner image 2', null, true, ['class' => 'img-fluid', 'style' => 'max-width: 130px; max-height: 90px; width: auto; height: auto; object-fit: contain;']) }}
                            </a>
                        @else
                            {{ RvMedia::image($config['image2'], 'partner image 2', null, true, ['class' => 'img-fluid', 'style' => 'max-width: 130px; max-height: 90px; width: auto; height: auto; object-fit: contain;']) }}
                        @endif
                    </div>
                </div>
            @endif
            
            @if ($config['image3'])
                <div class="col-auto">
                    <div class="partner-image">
                        @if (($url = $config['url3']) && $url !== '#')
                            <a href="{{ $url }}" target="_blank">
                                {{ RvMedia::image($config['image3'], 'partner image 3', null, true, ['class' => 'img-fluid', 'style' => 'max-width: 130px; max-height: 90px; width: auto; height: auto; object-fit: contain;']) }}
                            </a>
                        @else
                            {{ RvMedia::image($config['image3'], 'partner image 3', null, true, ['class' => 'img-fluid', 'style' => 'max-width: 130px; max-height: 90px; width: auto; height: auto; object-fit: contain;']) }}
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div> 