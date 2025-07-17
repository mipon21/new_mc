<?php

namespace Botble\DHL\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\DHL\Http\Middleware\WebhookMiddleware;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class DHLServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        if (! is_plugin_active('ecommerce')) {
            return;
        }

        $this->setNamespace('plugins/dhl')->loadHelpers();
    }

    public function boot(): void
    {
        if (! is_plugin_active('ecommerce')) {
            return;
        }

        $this
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->loadAndPublishConfigurations(['general'])
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function (): void {
            $this->app['router']->aliasMiddleware('dhl.webhook', WebhookMiddleware::class);
        });

        $config = $this->app['config'];
        if (! $config->has('logging.channels.dhl')) {
            $config->set([
                'logging.channels.dhl' => [
                    'driver' => 'daily',
                    'path' => storage_path('logs/dhl.log'),
                ],
            ]);
        }

        $this->app->register(HookServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);
    }
} 