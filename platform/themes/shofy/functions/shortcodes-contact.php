<?php

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\FieldOptions\ShortcodeTabsFieldOption;
use Botble\Shortcode\Forms\Fields\ShortcodeTabsField;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;

app()->booted(function (): void {
    if (! is_plugin_active('contact')) {
        return;
    }

    add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
        return Theme::getThemeNamespace('partials.shortcodes.contact-form.index');
    });

    Shortcode::setPreviewImage('contact-form', Theme::asset()->url('images/shortcodes/contact-form.png'));

    Shortcode::modifyAdminConfig('contact-form', function (ShortcodeForm $form) {
        $attributes = is_array($form->getModel()) ? $form->getModel() : [];

        return $form
            ->add(
                'background_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Background image'))
                    ->helperText(__('Recommended size: 1920x600px for optimal display'))
            )
            ->add(
                'background_overlay_opacity',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Background overlay opacity'))
                    ->helperText(__('Set overlay opacity from 0 (transparent) to 100 (opaque). Default is 60.'))
                    ->defaultValue(60)
                    ->min(0)
                    ->max(100)
            )
            ->add(
                'show_contact_form',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(__('Show contact form'))
                    ->attributes(['data-bb-toggle' => 'collapse', 'data-bb-target' => '.contact-form-wrapper'])
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->wrapperAttributes([
                        'class' => 'contact-form-wrapper mb-3 position-relative',
                        'style' => sprintf(
                            'display: %s',
                            Arr::get($attributes, 'show_contact_form') ? 'block' : 'none'
                        ),
                    ])
            )
            ->add(
                'contact_info',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->fields([
                        'icon' => [
                            'type' => 'image',
                            'title' => __('Icon'),
                        ],
                        'content' => [
                            'type' => 'textarea',
                            'title' => __('Content'),
                        ],
                    ])
                    ->attrs($attributes)
                    ->max(5)
            )
            ->add(
                'icon_image_size',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Icon image size (px)'))
                    ->helperText(__('Enter the size of the icon image in pixels. It is used when the icon image is set.'))
                    ->defaultValue(60)
            )
            ->add(
                'show_social_info',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(__('Show social info'))
                    ->wrapperAttributes(['class' => 'mt-3'])
                    ->attributes(['data-bb-toggle' => 'collapse', 'data-bb-target' => '.social-info-wrapper'])
                    ->helperText(__('Manage the social links in Theme Options -> Social Links'))
            )
            ->add(
                'open_social_info_wrapper',
                HtmlField::class,
                [
                    'html' => sprintf(
                        '<fieldset class="form-fieldset social-info-wrapper" style="display: %s">',
                        Arr::get($attributes, 'show_social_info') ? 'block' : 'none'
                    ),
                ]
            )
            ->add(
                'social_info_label',
                TextField::class,
                TextFieldOption::make()->label(__('Social info label'))
            )
            ->add(
                'social_info_icon',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Social info icon'))
            )
            ->add('close_social_info_wrapper', HtmlField::class, ['html' => '</fieldset>']);
    });
});
