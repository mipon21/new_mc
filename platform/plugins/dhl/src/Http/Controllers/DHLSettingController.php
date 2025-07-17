<?php

namespace Botble\DHL\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Supports\SettingStore;
use Illuminate\Http\Request;

class DHLSettingController extends BaseController
{
    public function update(Request $request, BaseHttpResponse $response, SettingStore $settingStore)
    {
        $data = $request->except(['_token']);
        foreach ($data as $settingKey => $settingValue) {
            $settingStore->set($settingKey, $settingValue);
        }

        $settingStore->save();

        return $response
            ->setNextUrl(route('shipping_methods.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
} 