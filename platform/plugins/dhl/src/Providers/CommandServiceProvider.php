<?php

namespace Botble\DHL\Providers;

use Botble\DHL\Commands\InitDHLCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InitDHLCommand::class,
            ]);
        }
    }
} 