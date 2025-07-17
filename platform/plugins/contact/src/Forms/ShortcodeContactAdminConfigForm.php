<?php

namespace Botble\Contact\Forms;

use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Contact\Models\CustomField;
use Botble\Shortcode\Forms\ShortcodeForm;

class ShortcodeContactAdminConfigForm extends ShortcodeForm
{
    public function setup(): void
    {
        parent::setup();

        $fields = [
            'phone' => trans('plugins/contact::contact.sender_phone'),
            'email' => trans('plugins/contact::contact.form_email'),
            'subject' => trans('plugins/contact::contact.form_subject'),
            'address' => trans('plugins/contact::contact.form_address'),
        ];

        // Get available custom fields
        $customFields = CustomField::query()
            ->wherePublished()
            ->orderBy('order')
            ->pluck('name', 'id')
            ->toArray();

        // Get current shortcode attributes to check for existing values
        $attributes = is_array($this->getModel()) ? $this->getModel() : [];

        $this
            ->add(
                'display_fields',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(trans('plugins/contact::contact.display_fields'))
                    ->choices($fields)
                    ->defaultValue(array_keys($fields))
            )
            ->add(
                'mandatory_fields',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(trans('plugins/contact::contact.mandatory_fields'))
                    ->helperText(trans('plugins/contact::contact.mandatory_fields_helper_text'))
                    ->choices($fields)
                    ->defaultValue(['email'])
            )
            ->when(! empty($customFields), function (ShortcodeForm $form) use ($customFields, $attributes): void {
                $fieldOption = MultiChecklistFieldOption::make()
                    ->label(trans('plugins/contact::contact.display_custom_fields'))
                    ->helperText(trans('plugins/contact::contact.display_custom_fields_helper_text'))
                    ->choices($customFields);
                
                // Only set default value for new shortcodes (when no saved value exists)
                // Default to no custom fields selected for cleaner UX
                if (!isset($attributes['display_custom_fields'])) {
                    $fieldOption->defaultValue([]);
                }
                
                $form->add(
                    'display_custom_fields',
                    MultiCheckListField::class,
                    $fieldOption
                );
            });
    }
}
