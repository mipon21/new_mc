@php
    use Botble\Shortcode\Facades\Shortcode;

    Theme::asset()->remove('contact-css');
    Theme::asset()->container('footer')->remove('contact-public-js');

    $contactInfo = Shortcode::fields()->getTabsData(['icon', 'content'], $shortcode);

    $iconImageSize = $shortcode->icon_image_size ?: 60;
    $backgroundImage = $shortcode->background_image ? RvMedia::getImageUrl($shortcode->background_image) : null;
    $overlayOpacity = $shortcode->background_overlay_opacity ?: 60;
@endphp

<section class="tp-contact-section pb-100">
    <div class="container">
        <div class="tp-contact-inner" 
                            style="
                @if($backgroundImage)
                    background-image: url('{{ $backgroundImage }}');
                    background-size: cover;
                    background-position: center center;
                    background-repeat: no-repeat;
                    position: relative;
                    min-height: 500px;
                    border-radius: 20px;
                    overflow: hidden;
                @endif
                padding: 42px 30px 49px;
                @if(!$backgroundImage)
                    background: var(--tp-common-white);
                @endif
                box-shadow: 0px 30px 70px rgba(1, 15, 28, 0.1);
            "
        >
            @if($backgroundImage)
                <div class="tp-contact-bg-overlay" 
                    style="
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, {{ ($overlayOpacity / 100) * 0.3 }});
                        z-index: 1;
                        border-radius: 20px;
                    "></div>
            @endif
            
            <div class="row" style="@if($backgroundImage) position: relative; z-index: 2; @endif">
                @if ($shortcode->show_contact_form)
                    <div class="col-xl-7 col-lg-7">
                        <div class="tp-contact-wrapper">
                            @if ($title = $shortcode->title)
                                <h3 class="tp-contact-title" style="@if($backgroundImage) color: white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); @endif">{{ $title }}</h3>
                            @endif

                            <div class="tp-contact-form">
                                @if($backgroundImage)
                                    <style>
                                        .tp-contact-input input,
                                        .tp-contact-input textarea {
                                            background: rgba(255, 255, 255, 0.95) !important;
                                            border: 1px solid rgba(255, 255, 255, 0.4) !important;
                                            backdrop-filter: blur(3px);
                                        }
                                        .tp-contact-input-title label {
                                            background-color: rgba(255, 255, 255, 0.95) !important;
                                            color: var(--tp-common-black) !important;
                                        }
                                        .tp-contact-btn {
                                            background-color: rgba(0, 0, 0, 0.9) !important;
                                            backdrop-filter: blur(3px);
                                        }
                                    </style>
                                @endif
                                
                                <style>
                                    /* Stack form fields vertically and control width */
                                    .tp-contact-form .row {
                                        display: flex;
                                        flex-direction: column;
                                        gap: 0;
                                    }
                                    .tp-contact-form .col-sm-6,
                                    .tp-contact-form .col-12 {
                                        width: 100% !important;
                                        max-width: 100% !important;
                                        flex: none !important;
                                        margin-bottom: 0 !important;
                                    }
                                    .tp-contact-input-box {
                                        margin-bottom: 25px !important;
                                        width: 100%;
                                    }
                                    .tp-contact-input input {
                                        width: 100% !important;
                                        max-width: 100% !important;
                                        box-sizing: border-box;
                                        padding: 15px 20px !important;
                                        margin: 0 !important;
                                    }
                                    
                                    /* Fix textarea to match input field width exactly */
                                    
                                    /* Reset any previous constraints on form rows */
                                    .tp-contact-form .contact-form-row {
                                        max-width: none !important;
                                        width: 100% !important;
                                    }
                                    
                                    /* Set textarea to exact same width as input fields (col-md-6 equivalent) */
                                    .tp-contact-form textarea,
                                    .tp-contact-form textarea[name="content"] {
                                        width: calc(50% - 15px) !important;
                                        max-width: calc(50% - 15px) !important;
                                        min-width: calc(50% - 15px) !important;
                                        resize: vertical !important;
                                        box-sizing: border-box !important;
                                        border: 1px solid #e0e2e3 !important;
                                        padding: 15px 20px !important;
                                        margin: 0 !important;
                                        display: block !important;
                                    }
                                    
                                    /* Ensure input fields maintain proper width */
                                    .tp-contact-form input[type="text"],
                                    .tp-contact-form input[type="email"],
                                    .tp-contact-form input[type="tel"] {
                                        width: 100% !important;
                                        max-width: 100% !important;
                                    }
                                    
                                    /* Ensure input field containers maintain proper width */
                                    .tp-contact-form .contact-column-6 {
                                        width: 50% !important;
                                        max-width: 50% !important;
                                    }
                                    
                                    /* Fix custom fields to match regular field width */
                                    .tp-contact-form .contact-column-12,
                                    .tp-contact-form div[class*="contact-field-"],
                                    .tp-contact-form div[class*="custom_field_"],
                                    .tp-contact-form .form-group,
                                    .tp-contact-form .checkbox,
                                    .tp-contact-form div[class*="checkbox"],
                                    .tp-contact-form div[class*="terms"] {
                                        width: 50% !important;
                                        max-width: 50% !important;
                                        padding-right: 15px !important;
                                        float: left !important;
                                        box-sizing: border-box !important;
                                    }
                                    
                                    /* Target custom field inputs, selects, and textareas */
                                    .tp-contact-form div[class*="contact-field-"] input,
                                    .tp-contact-form div[class*="contact-field-"] select,
                                    .tp-contact-form div[class*="contact-field-"] textarea,
                                    .tp-contact-form div[class*="custom_field_"] input,
                                    .tp-contact-form div[class*="custom_field_"] select,
                                    .tp-contact-form div[class*="custom_field_"] textarea,
                                    .tp-contact-form .contact-column-12 input,
                                    .tp-contact-form .contact-column-12 select,
                                    .tp-contact-form .contact-column-12 textarea {
                                        width: 100% !important;
                                        max-width: 100% !important;
                                        box-sizing: border-box !important;
                                    }
                                    
                                    /* Mobile responsive: Make textarea full width on mobile devices */
                                    @media (max-width: 768px) {
                                        .tp-contact-form textarea,
                                        .tp-contact-form textarea[name="content"] {
                                            width: 100% !important;
                                            max-width: 100% !important;
                                            min-width: 100% !important;
                                        }
                                        
                                        /* Also make input field containers full width on mobile */
                                        .tp-contact-form .contact-column-6,
                                        .tp-contact-form .contact-column-12,
                                        .tp-contact-form div[class*="contact-field-"],
                                        .tp-contact-form div[class*="custom_field_"],
                                        .tp-contact-form .form-group,
                                        .tp-contact-form .checkbox,
                                        .tp-contact-form div[class*="checkbox"],
                                        .tp-contact-form div[class*="terms"] {
                                            width: 100% !important;
                                            max-width: 100% !important;
                                        }
                                    }
                                    

                                    
                                    /* Form container width control */
                                    .tp-contact-form {
                                        width: 100%;
                                        max-width: 100%;
                                        overflow: hidden;
                                    }
                                    
                                    /* Aggressively reduce white space between form and content */
                                    .tp-contact-inner .row {
                                        margin-left: -5px !important;
                                        margin-right: -5px !important;
                                    }
                                    
                                    .tp-contact-inner .col-xl-7 {
                                        padding-right: 5px !important;
                                    }
                                    
                                    .tp-contact-inner .col-xl-5 {
                                        padding-left: 5px !important;
                                    }
                                    
                                    /* OPTIMIZE LAYOUT: col-xl-7 + col-xl-5 = Better spacing */
                                    .tp-contact-wrapper {
                                        width: 120% !important;
                                        max-width: 120% !important;
                                        margin-right: -20px !important;
                                        margin-left: 0px !important;
                                        padding-right: 0px !important;
                                        padding-left: 0px !important;
                                    }
                                    
                                    /* Expand the form content within the wrapper */
                                    .tp-contact-form,
                                    .tp-contact-form .row {
                                        width: 100% !important;
                                        max-width: 100% !important;
                                        margin: 0 !important;
                                    }
                                    
                                    /* Ensure form fields maintain good width */
                                    .tp-contact-form input,
                                    .tp-contact-form textarea,
                                    .tp-contact-form select {
                                        min-width: 300px !important;
                                    }
                                    
                                    .tp-contact-info-wrapper {
                                        margin-left: -30px !important;
                                        padding-left: 0px !important;
                                    }
                                    
                                    /* Responsive adjustments */
                                    @media (max-width: 768px) {
                                        .tp-contact-wrapper {
                                            width: 100% !important;
                                            max-width: 100% !important;
                                            margin-right: 0px !important;
                                            padding-right: 0;
                                        }
                                        
                                        .tp-contact-form input,
                                        .tp-contact-form textarea,
                                        .tp-contact-form select {
                                            min-width: auto !important;
                                        }
                                        
                                        .tp-contact-info-wrapper {
                                            margin-left: 0px !important;
                                        }
                                    }
                                </style>
                                
                                {!! $form->renderForm() !!}
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-xl-5 col-lg-5">
                    <div class="tp-contact-info-wrapper"
                        style="
                            @if($backgroundImage)
                                background: rgba(255, 255, 255, 0.1);
                                backdrop-filter: blur(10px);
                                border: 1px solid rgba(255, 255, 255, 0.2);
                                border-radius: 15px;
                                padding: 30px 20px;
                                margin-top: 0;
                            @else
                                margin-left: 0px;
                                padding-top: 20px;
                            @endif
                        "
                    >
                        @foreach ($contactInfo as $info)
                            @continue(empty($info['icon']) || empty($info['content']))

                            <div class="tp-contact-info-item">
                                <div class="tp-contact-info-icon">
                                    <span>
                                        {{ RvMedia::image($info['icon'], $info['content'], attributes: ['style' => sprintf('max-width: %spx !important; max-height: %spx !important;', $iconImageSize, $iconImageSize)]) }}
                                    </span>
                                </div>
                                <div class="tp-contact-info-content">
                                    <p style="@if($backgroundImage) color: white; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); @endif">{!! BaseHelper::clean($info['content']) !!}</p>
                                </div>
                            </div>
                        @endforeach

                        @if ($shortcode->show_social_info)
                            @php
                                $socialInfoLabel = $shortcode->social_info_label;
                            @endphp

                            <div class="tp-contact-info-item">
                                <div class="tp-contact-info-icon">
                                    <span>
                                        {{ RvMedia::image($shortcode->social_info_icon, $socialInfoLabel ?: __('Social Media'), attributes: ['style' => sprintf('max-width: %spx !important; max-height: %spx !important;', $iconImageSize, $iconImageSize)]) }}
                                    </span>
                                </div>
                                <div class="tp-contact-info-content">
                                    <div class="tp-contact-social-wrapper mt-5">
                                        @if ($socialInfoLabel)
                                            <h4 class="tp-contact-social-title" style="@if($backgroundImage) color: white; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); @endif">{{ $socialInfoLabel }}</h4>
                                        @endif

                                        @if ($socialLinks = Theme::getSocialLinks())
                                            <div class="tp-contact-social-icon">
                                                @foreach($socialLinks as $socialLink)
                                                    <a href="{{ $socialLink->getUrl() }}" 
                                                        style="@if($backgroundImage) background-color: rgba(255, 255, 255, 0.9); border-color: rgba(255, 255, 255, 0.3); backdrop-filter: blur(5px); @endif"
                                                    >{{ $socialLink->getIconHtml() }}</a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
