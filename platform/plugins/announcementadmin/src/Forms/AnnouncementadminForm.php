<?php

namespace Botble\Announcementadmin\Forms;

use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Announcementadmin\Http\Requests\AnnouncementadminRequest;
use Botble\Announcementadmin\Models\Announcementadmin;

class AnnouncementadminForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Announcementadmin::class)
            ->setValidatorClass(AnnouncementadminRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
