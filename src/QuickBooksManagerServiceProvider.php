<?php

namespace Hotrush\QuickBooksManager;

use Illuminate\Support\ServiceProvider;

class QuickBooksManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->definePublishingGroups();
            $this->defineMigrations();
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/route.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/quickbooks_manager.php', 'quickbooks_manager'
        );

        $this->app->singleton(QuickBooksManager::class, function ($app) {
            return new QuickBooksManager($app);
        });
    }

    private function definePublishingGroups()
    {
        $this->publishes([
            __DIR__ . '/../config/quickbooks_manager.php' => config_path('quickbooks_manager.php'),
        ], 'config');
    }

    private function defineMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
