<?php

namespace Kazinokib\BdappsApi;

use Illuminate\Support\ServiceProvider;
use Kazinokib\BdappsApi\Services\SmsService;
use Kazinokib\BdappsApi\Services\UssdService;
use Kazinokib\BdappsApi\Services\CaasService;
use Kazinokib\BdappsApi\Services\OtpService;
use Kazinokib\BdappsApi\Services\SubscriptionService;

class BdappsApiServiceProvider extends ServiceProvider
{
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

        $this->app->singleton(SubscriptionService::class, function ($app) {
            return new SubscriptionService($app['config']['bdappsapi']);
        });

        $this->app->singleton(BdappsApi::class, function ($app) {
            return new BdappsApi(
                $app[SmsService::class],
                $app[UssdService::class],
                $app[CaasService::class],
                $app[OtpService::class],
                $app[SubscriptionService::class]
            );
        });

        $this->app->alias(BdappsApi::class, 'bdappsapi');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/bdappsapi.php' => config_path('bdappsapi.php'),
        ], 'config');
    }
}
