<?php

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;

app()->booted(function (): void {
    Shortcode::register('brands', __('Brands'), __('Add brands section'), function ($shortcode) {
        $style = $shortcode->style;
        $quantity = (int) $shortcode->quantity ?: 6;
        $title = $shortcode->title;
        $subtitle = $shortcode->subtitle;
        
        $brands = [];
        for ($i = 1; $i <= $quantity; $i++) {
            if ($shortcode->{'name_' . $i} && $shortcode->{'image_' . $i}) {
                $url = $shortcode->{'url_' . $i};
                // Format URL if it doesn't start with http:// or https://
                if (!empty($url) && !preg_match('~^(?:f|ht)tps?://~i', $url)) {
                    $url = 'https://' . $url;
                }
                
                $brands[] = [
                    'name' => $shortcode->{'name_' . $i},
                    'image' => $shortcode->{'image_' . $i},
                    'url' => $url,
                ];
            }
        }

        return view(Theme::getThemeNamespace() . '::partials.shortcodes.brands', compact('style', 'brands', 'title', 'subtitle'))->render();
    });

    Shortcode::setPreviewImage('brands', Theme::asset()->url('images/shortcodes/brands/style-1.png'));

    Shortcode::setAdminConfig('brands', function (array $attributes) {
        $form = ShortcodeForm::createFromArray($attributes)
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->placeholder(__('Enter title'))
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
                    ->placeholder(__('Enter subtitle'))
            )
            ->add(
                'style',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Style'))
                    ->choices([
                        'style-1' => __('Style 1'),
                    ])
                    ->defaultValue('style-1')
            )
            ->add(
                'quantity',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Number of brands'))
                    ->defaultValue(6)
                    ->min(1)
                    ->max(12)
            );

        // Add fields for each brand
        for ($i = 1; $i <= 12; $i++) {
            $form->add(
                'name_' . $i,
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Brand :number name', ['number' => $i]))
                    ->placeholder(__('Enter brand name'))
            )
            ->add(
                'image_' . $i,
                MediaImageField::class,
                [
                    'label' => __('Brand :number image', ['number' => $i]),
                    'value' => $attributes['image_' . $i] ?? null,
                ]
            )
            ->add(
                'url_' . $i,
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Brand :number URL', ['number' => $i]))
                    ->placeholder(__('Enter brand URL (e.g. https://example.com)'))
            );
        }

        return $form;
    });
}); 