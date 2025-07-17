<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class SettingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->booted(function () {
            $this->app->make(Setting::class)->setDefault([
                'language' => [
                    'default' => 'de',
                    'display' => 'all',
                    'hide_languages_in_list' => [],
                    'switcher_display' => 'dropdown',
                    'switcher_display_dropdown' => 'dropdown',
                    'switcher_display_list' => 'list',
                ],
            ]);
        });
    }
} 