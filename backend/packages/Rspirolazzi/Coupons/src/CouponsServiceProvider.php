<?php

namespace Rspirolazzi\Coupons;

use Illuminate\Support\ServiceProvider;

class CouponsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
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
        $this->mergeConfigFrom(__DIR__.'/../config/coupons.php', 'coupons');

        // Register the service the package provides.
        $this->app->singleton('coupons', function ($app) {
            return new Coupons;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['coupons'];
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
            __DIR__.'/../config/coupons.php' => config_path('coupons.php'),
        ], 'coupons.config');

        // Publishing the migrations file.
        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations'),
        ], 'migrations');
    }
}
