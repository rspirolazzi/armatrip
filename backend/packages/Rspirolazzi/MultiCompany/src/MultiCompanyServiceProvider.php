<?php

namespace Rspirolazzi\MultiCompany;

use Illuminate\Support\ServiceProvider;

class MultiCompanyServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'rspirolazzi');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'rspirolazzi');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/multicompany.php', 'multicompany');

        // Register the service the package provides.
        $this->app->singleton('multicompany', function ($app) {
            return new MultiCompany;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['multicompany'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/multicompany.php' => config_path('multicompany.php'),
        ], 'multicompany-config');

        // Publishing the migration file.
        if (!class_exists('CreateMultiCompanyTables')) {
            $timestamp = '2013_01_01_000000';
            $this->publishes([
                __DIR__ . '/../database/migrations/create_company_tables.stub' => $this->app->databasePath() . "/migrations/{$timestamp}_create_company_tables.php",
            ], 'multicompany-migrations');
        }
    }
}
