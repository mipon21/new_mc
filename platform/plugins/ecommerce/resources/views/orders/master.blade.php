<!DOCTYPE html>
<html {!! Theme::htmlAttributes() !!}>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title', __('Checkout')) </title>

    @if ($favicon = Theme::getFavicon())
        <link href="{{ RvMedia::getImageUrl($favicon) }}" rel="shortcut icon">
    @endif

    {!! Theme::typography()->renderCssVariables() !!}

    <style>
        :root {
            --primary-color:
                {{ $primaryColor = theme_option('primary_color', '#58b3f0') }}
            ;
            --primary-color-rgb:
                {{ implode(',', BaseHelper::hexToRgb($primaryColor)) }}
            ;
        }
    </style>

    {{-- Load theme assets first --}}
    @if (BaseHelper::isRtlEnabled())
        {!! Html::style(Theme::asset()->url('plugins/bootstrap/bootstrap.rtl.min.css')) !!}
        {!! Html::style(Theme::asset()->url('css/theme-rtl.css?v=' . get_cms_version())) !!}
    @else
        {!! Html::style(Theme::asset()->url('plugins/bootstrap/bootstrap.min.css')) !!}
    @endif

    {!! Html::style(Theme::asset()->url('css/animate.css')) !!}
    {!! Html::style(Theme::asset()->url('plugins/swiper/swiper-bundle.css')) !!}
    {!! Html::style('vendor/core/plugins/ecommerce/libraries/slick/slick.css') !!}
    {!! Html::style(Theme::asset()->url('css/theme.css?v=' . get_cms_version())) !!}

    {{-- Ecommerce specific styles --}}
    {!! Html::style('vendor/core/core/base/libraries/font-awesome/css/fontawesome.min.css?v=' . ($assetsVersion = EcommerceHelper::getAssetVersion())) !!}
    {!! Html::style('vendor/core/core/base/libraries/ckeditor/content-styles.css?v=' . $assetsVersion) !!}
    @if (BaseHelper::isRtlEnabled())
        {!! Html::style('vendor/core/plugins/ecommerce/libraries/bootstrap/bootstrap.rtl.min.css?v=' . $assetsVersion) !!}
    @else
        {!! Html::style('vendor/core/plugins/ecommerce/libraries/bootstrap/bootstrap.min.css?v=' . $assetsVersion) !!}
    @endif

    {!! Html::style('vendor/core/plugins/ecommerce/css/front-theme.css?v=' . $assetsVersion) !!}

    @if (BaseHelper::isRtlEnabled())
        {!! Html::style('vendor/core/plugins/ecommerce/css/front-theme-rtl.css?v=' . $assetsVersion) !!}
    @endif

    {!! Html::style('vendor/core/core/base/libraries/toastr/toastr.min.css?v=' . $assetsVersion) !!}

    {!! Html::script('vendor/core/plugins/ecommerce/js/checkout.js?v=' . $assetsVersion) !!}

    @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
        <link href="{{ asset('vendor/core/core/base/libraries/select2/css/select2.min.css?v=' . $assetsVersion) }}"
            rel="stylesheet">
        <script src="{{ asset('vendor/core/core/base/libraries/select2/js/select2.min.js?v=' . $assetsVersion) }}"></script>
        <script src="{{ asset('vendor/core/plugins/location/js/location.js?v=' . $assetsVersion) }}"></script>
    @endif

    {{-- Include theme header assets --}}
    {!! Theme::partial('header-meta') !!}
    {!! Theme::header() !!}

    {!! apply_filters('ecommerce_checkout_header', null) !!}

    @stack('header')
</head>

@php
    Theme::addBodyAttributes([
        'class' => 'checkout-page',
    ]);

    // Define header variables and share them with all views to prevent undefined variable errors
    $headerTopBackgroundColor = theme_option('header_top_background_color', '#010f1c');
    $headerTopTextColor = theme_option('header_top_text_color', '#fff');
    $headerMainBackgroundColor = theme_option('header_main_background_color', '#fff');
    $headerMainTextColor = theme_option('header_main_text_color', '#010f1c');

    // Share variables with all views
    view()->share(compact(
        'headerTopBackgroundColor',
        'headerTopTextColor',
        'headerMainBackgroundColor',
        'headerMainTextColor'
    ));
@endphp

<body{!! Theme::bodyAttributes() !!}>
    {!! apply_filters(THEME_FRONT_BODY, null) !!}
    {!! apply_filters('ecommerce_checkout_body', null) !!}

    {{-- Include theme header content (pre-header, header, navigation, etc.) --}}
    {!! apply_filters('theme_front_header_content', null) !!}

    <main>
        {{-- Checkout content --}}
        <div class="container my-0 my-md-3 my-lg-5 checkout-content-wrap">
            @yield('content')
        </div>
    </main>

    {{-- Include theme footer --}}
    {!! apply_filters('theme_front_footer_content', null) !!}

    @stack('footer')

    {{-- Theme JavaScript assets --}}
    {!! Html::script(Theme::asset()->url('js/jquery-3.7.1.min.js')) !!}
    {!! Html::script(Theme::asset()->url('plugins/bootstrap/bootstrap.min.js'), ['defer' => true]) !!}
    {!! Html::script(Theme::asset()->url('js/meanmenu.js'), ['defer' => true]) !!}
    {!! Html::script(Theme::asset()->url('plugins/swiper/swiper-bundle.js?v=' . get_cms_version()), ['defer' => true]) !!}
    {!! Html::script('vendor/core/plugins/ecommerce/libraries/slick/slick.min.js', ['defer' => true]) !!}
    {!! Html::script(Theme::asset()->url('js/theme.js?v=' . get_cms_version()), ['defer' => true]) !!}

    @if (is_plugin_active('ecommerce'))
        {!! Html::script(Theme::asset()->url('js/ecommerce.js?v=' . get_cms_version()), ['defer' => true]) !!}
    @endif

    {{-- Ecommerce specific scripts --}}
    {!! Html::script('vendor/core/plugins/ecommerce/js/utilities.js?v=' . $assetsVersion) !!}
    {!! Html::script('vendor/core/core/base/libraries/toastr/toastr.min.js?v=' . $assetsVersion) !!}

    <script type="text/javascript">
        window.messages = {
            error_header: '{{ __('Error') }}',
            success_header: '{{ __('Success') }}',
        }
    </script>

    @if (session()->has('success_msg') || session()->has('error_msg') || isset($errors))
        <script type="text/javascript">
            $(document).ready(function () {
                @if (session()->has('success_msg') && session('success_msg'))
                    MainCheckout.showNotice('success', '{{ session('success_msg') }}');
                @endif
                @if (session()->has('error_msg'))
                    MainCheckout.showNotice('error', '{{ session('error_msg') }}');
                @endif
                @if (isset($errors) && $errors->count())
                    MainCheckout.showNotice('error', '{{ $errors->first() }}');
                @endif
                            });
        </script>
    @endif

    {!! apply_filters('ecommerce_checkout_footer', null) !!}
    {!! Theme::footer() !!}

    </body>

</html>