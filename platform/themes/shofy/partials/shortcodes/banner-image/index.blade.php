@php
    $image = $shortcode->image;
    $url = $shortcode->url;
    $altText = $shortcode->alt_text ?: 'Banner Image';
    $maxWidth = $shortcode->max_width ?: '100%';
    $alignment = $shortcode->alignment ?: 'center';

    $containerClass = 'tp-banner-image-container';
    $alignmentClass = 'tp-banner-align-' . $alignment;

    // Build proper container style for centering
    $containerStyle = '';
    if ($maxWidth !== '100%') {
        switch ($alignment) {
            case 'left':
                $containerStyle = "max-width: {$maxWidth}; margin-left: 0; margin-right: auto;";
                break;
            case 'right':
                $containerStyle = "max-width: {$maxWidth}; margin-left: auto; margin-right: 0;";
                break;
            default: // center
                $containerStyle = "max-width: {$maxWidth}; margin-left: auto; margin-right: auto;";
                break;
        }
    }

    // Ensure URL is properly formatted to avoid language manager URL parsing issues
    if ($url && !empty($url)) {
        // If URL doesn't start with http:// or https://, add https://
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }
        // Clean the URL to prevent parsing issues
        $url = trim($url);
    }
@endphp

<div class="{{ $containerClass }} {{ $alignmentClass }}" @if($containerStyle) style="{{ $containerStyle }}" @endif>
    @if($url)
        <a href="{!! $url !!}" class="tp-banner-image-link">
    @endif
        <div class="tp-banner-image-wrapper">
            {{ RvMedia::image($image, $altText, attributes: [
    'class' => 'tp-banner-image img-fluid',
    'loading' => 'lazy'
]) }}
        </div>
        @if($url)
            </a>
        @endif
</div>