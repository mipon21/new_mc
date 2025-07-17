<?php

use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;

class SitePartnerImagesWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Site Partner Images'),
            'description' => __('Display partner images with external links.'),
            'image1' => null,
            'url1' => null,
            'image2' => null,
            'url2' => null,
            'image3' => null,
            'url3' => null,
        ]);
    }

    protected function settingForm(): WidgetForm|string|null
    {
        return WidgetForm::createFromArray($this->getConfig())
            ->add(
                'image1',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image 1'))
                    ->attributes([
                        'name' => 'image1',
                        'value' => $this->getConfig('image1'),
                    ])
            )
            ->add(
                'url1',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('URL 1'))
                    ->attributes([
                        'name' => 'url1',
                        'value' => $this->getConfig('url1'),
                    ])
            )
            ->add(
                'image2',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image 2'))
                    ->attributes([
                        'name' => 'image2',
                        'value' => $this->getConfig('image2'),
                    ])
            )
            ->add(
                'url2',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('URL 2'))
                    ->attributes([
                        'name' => 'url2',
                        'value' => $this->getConfig('url2'),
                    ])
            )
            ->add(
                'image3',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image 3'))
                    ->attributes([
                        'name' => 'image3',
                        'value' => $this->getConfig('image3'),
                    ])
            )
            ->add(
                'url3',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('URL 3'))
                    ->attributes([
                        'name' => 'url3',
                        'value' => $this->getConfig('url3'),
                    ])
            );
    }
} 