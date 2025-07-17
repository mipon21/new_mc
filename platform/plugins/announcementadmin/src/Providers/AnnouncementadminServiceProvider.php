<?php

namespace Botble\Announcementadmin\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Base\Facades\DashboardMenu;
use Botble\Announcementadmin\Models\Announcementadmin;
use Botble\Base\Facades\EmailHandler;
use Illuminate\Routing\Events\RouteMatched;

class AnnouncementadminServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/announcementadmin')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'email'])
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadMigrations();

        $this->registerEventServiceProvider();
        $this->registerLanguageAdvancedModule();
        $this->registerEmailTemplateSettings();
    }

    /**
     * Register the EventServiceProvider.
     */
    protected function registerEventServiceProvider(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register the Language Advanced Module if the constant is defined.
     */
    protected function registerLanguageAdvancedModule(): void
    {
        if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Announcementadmin::class, [
                'name',
            ]);
        }
    }

    /**
     * Register email template settings for the module.
     */
    protected function registerEmailTemplateSettings(): void
    {
        $this->app['events']->listen(RouteMatched::class, function (): void {
            EmailHandler::addTemplateSettings(
                ANNOUNCEMENTADMIN_MODULE_SCREEN_NAME,
                config('plugins.announcementadmin.email', [])
            );
        });
    }
}
