<?php

namespace Kazinokib\BdappsApi;

use Illuminate\Support\ServiceProvider;
use Kazinokib\BdappsApi\Services\SmsService;
use Kazinokib\BdappsApi\Services\UssdService;
use Kazinokib\BdappsApi\Services\CaasService;
use Kazinokib\BdappsApi\Services\OtpService;

/**
 * Class BdappsApiServiceProvider
 *
 * This service provider is responsible for registering the BdappsApi services
 * and configuration with the Laravel application.
 *
 * @package Kazinokib\BdappsApi
 */
class BdappsApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method is called by Laravel during the service provider registration process.
     * It binds the BdappsApi services to the service container and merges the package
     * configuration.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bdappsapi.php', 'bdappsapi');

        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService($app['config']['bdappsapi']);
        });

        $this->app->singleton(UssdService::class, function ($app) {
            return new UssdService($app['config']['bdappsapi']);
        });

        $this->app->singleton(CaasService::class, function ($app) {
            return new CaasService($app['config']['bdappsapi']);
        });

        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService($app['config']['bdappsapi']);
        });

        $this->app->singleton(BdappsApi::class, function ($app) {
            return new BdappsApi(
                $app[SmsService::class],
                $app[UssdService::class],
                $app[CaasService::class],
                $app[OtpService::class]
            );
        });

        $this->app->alias(BdappsApi::class, 'bdappsapi');
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called by Laravel after all services have been registered.
     * It publishes the package configuration file to the application's config directory.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/bdappsapi.php' => config_path('bdappsapi.php'),
        ], 'config');
    }
}
