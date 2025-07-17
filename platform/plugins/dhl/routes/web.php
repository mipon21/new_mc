<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\DHL\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        Route::group([
            'prefix' => 'shipments/dhl',
            'as' => 'ecommerce.shipments.dhl.',
            'permission' => 'ecommerce.shipments.index',
        ], function (): void {
            Route::controller('DHLController')->group(function (): void {
                Route::get('show/{id}', [
                    'as' => 'show',
                    'uses' => 'show',
                ]);

                Route::post('transactions/create/{id}', [
                    'as' => 'transactions.create',
                    'uses' => 'createTransaction',
                    'permission' => 'ecommerce.shipments.edit',
                ]);

                Route::get('rates/{id}', [
                    'as' => 'rates',
                    'uses' => 'getRates',
                ]);

                Route::post('update-rate/{id}', [
                    'as' => 'update-rate',
                    'uses' => 'updateRate',
                    'permission' => 'ecommerce.shipments.edit',
                ]);

                Route::get('view-logs/{file}', [
                    'as' => 'view-log',
                    'uses' => 'viewLog',
                ]);
            });

            Route::group(['prefix' => 'settings', 'as' => 'settings.'], function (): void {
                Route::post('update', [
                    'as' => 'update',
                    'uses' => 'DHLSettingController@update',
                    'middleware' => 'preventDemo',
                    'permission' => 'shipping_methods.index',
                ]);
            });
        });
    });

    if (is_plugin_active('marketplace')) {
        Theme::registerRoutes(function (): void {
            Route::group([
                'prefix' => 'vendor',
                'as' => 'marketplace.vendor.',
                'middleware' => ['vendor'],
            ], function (): void {
                Route::group(['prefix' => 'orders', 'as' => 'orders.'], function (): void {
                    Route::group(['prefix' => 'dhl', 'as' => 'dhl.'], function (): void {
                        Route::controller('DHLController')->group(function (): void {
                            Route::get('show/{id}', [
                                'as' => 'show',
                                'uses' => 'show',
                            ]);

                            Route::post('transactions/create/{id}', [
                                'as' => 'transactions.create',
                                'uses' => 'createTransaction',
                            ]);

                            Route::get('rates/{id}', [
                                'as' => 'rates',
                                'uses' => 'getRates',
                            ]);

                            Route::post('update-rate/{id}', [
                                'as' => 'update-rate',
                                'uses' => 'updateRate',
                            ]);
                        });
                    });
                });
            });
        });
    }
});

Route::group([
    'namespace' => 'Botble\DHL\Http\Controllers',
    'prefix' => 'dhl',
    'middleware' => ['api', 'dhl.webhook'],
    'as' => 'dhl.',
], function (): void {
    Route::controller('DHLWebhookController')->group(function (): void {
        Route::post('webhooks', [
            'uses' => 'index',
            'as' => 'webhooks',
        ]);
    });
}); 