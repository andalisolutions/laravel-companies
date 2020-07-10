<?php

declare(strict_types=1);

namespace Andali\Companies\Providers;

use Illuminate\Support\ServiceProvider;

class CompaniesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/companies.php', 'companies');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadViewsFrom(__DIR__.'/../../resources/views', 'companies');

            $this->publishes([
                __DIR__.'/../../config/companies.php' => $this->app->configPath('companies.php'),
            ], 'companies-config');

            $this->publishes([
                __DIR__.'/../../database/migrations/' => $this->app->databasePath('/migrations'),
            ], 'companies-migrations');

            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        }
    }
}
