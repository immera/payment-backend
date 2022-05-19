<?php

namespace Adiechahk\PaymentBackend;

use Adiechahk\PaymentBackend\Models\PaymentInstance;
use Adiechahk\PaymentBackend\Observers\PaymentInstanceObserver;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'adiechahk');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'adiechahk');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Observer
        PaymentInstance::observe(PaymentInstanceObserver::class);

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
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/payment.php', 'payment');

        // Register the service the package provides.
        $this->app->singleton('payment', function ($app) {
            return new Payment;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['payment'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/payment.php' => config_path('payment.php'),
        ], 'payment.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/adiechahk'),
        ], 'payment-backend.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/adiechahk'),
        ], 'payment-backend.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/adiechahk'),
        ], 'payment-backend.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
