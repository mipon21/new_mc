<?php

namespace Botble\Announcementadmin\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Announcementadmin\Http\Requests\AnnouncementadminRequest;
use Botble\Announcementadmin\Models\Announcementadmin;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Announcementadmin\Tables\AnnouncementadminTable;
use Botble\Announcementadmin\Forms\AnnouncementadminForm;

class AnnouncementadminController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans(trans('plugins/announcementadmin::announcementadmin.name')), route('announcementadmin.index'));
    }

    public function index(AnnouncementadminTable $table)
    {
        $this->pageTitle(trans('plugins/announcementadmin::announcementadmin.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/announcementadmin::announcementadmin.create'));

        return AnnouncementadminForm::create()->renderForm();
    }

    public function store(AnnouncementadminRequest $request)
    {
        $form = AnnouncementadminForm::create()->setRequest($request);

        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('announcementadmin.index'))
            ->setNextUrl(route('announcementadmin.edit', $form->getModel()->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Announcementadmin $announcementadmin)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $announcementadmin->name]));

        return AnnouncementadminForm::createFromModel($announcementadmin)->renderForm();
    }

    public function update(Announcementadmin $announcementadmin, AnnouncementadminRequest $request)
    {
        AnnouncementadminForm::createFromModel($announcementadmin)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('announcementadmin.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Announcementadmin $announcementadmin)
    {
        return DeleteResourceAction::make($announcementadmin);
    }
}
