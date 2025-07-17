<?php

use Botble\Base\Facades\AdminHelper;
use Botble\LanguageAdvanced\Http\Controllers\LanguageAdvancedController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group([
        'controller' => LanguageAdvancedController::class,
        'prefix' => 'language-advanced',
        'middleware' => ['web', 'core', 'auth'],
        'as' => 'language-advanced.',
    ], function (): void {
        Route::post('save/{id}', [
            'as' => 'save',
            'uses' => 'save',
            'permission' => false,
        ])->wherePrimaryKey();
    });
});
