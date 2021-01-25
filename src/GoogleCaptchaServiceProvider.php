<?php

namespace mithatguner\googlecaptcha;

use Illuminate\Support\ServiceProvider;

class GoogleCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $app = $this->app;

        $this->bootConfig();

        $app['validator']->extend('googlecaptcha', function ($attribute, $value) use ($app) {
            return $app['googlecaptcha']->verifyResponse($value, $app['request']->getClientIp());
        });

        if ($app->bound('form')) {
            $app['form']->macro('googlecaptcha', function ($attributes = []) use ($app) {
                return $app['googlecaptcha']->display($attributes, $app->getLocale());
            });
        }
    }

    /**
     * Booting configure.
     */
    protected function bootConfig()
    {
        $path = __DIR__.'/config/googlecaptcha.php';

        $this->mergeConfigFrom($path, 'googlecaptcha');

        if (function_exists('config_path')) {
            $this->publishes([$path => config_path('googlecaptcha.php')]);
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('googlecaptcha', function ($app) {
            return new NoCaptcha(
                $app['config']['googlecaptcha.secret'],
                $app['config']['googlecaptcha.sitekey'],
                $app['config']['googlecaptcha.options']
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['googlecaptcha'];
    }
}
