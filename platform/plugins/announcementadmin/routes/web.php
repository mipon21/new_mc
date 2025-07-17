<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Announcementadmin\Http\Controllers\AnnouncementadminController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['prefix' => 'announcementadmins', 'as' => 'announcementadmin.'], function () {
        Route::resource('', AnnouncementadminController::class)->parameters(['' => 'announcementadmin']);
    });
});
