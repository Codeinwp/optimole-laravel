<?php

declare(strict_types=1);

/*
 * This file is part of Optimole Laravel Package.
 *
 * (c) Optimole Team <friends@optimole.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Optimole\Laravel;

use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;
use Illuminate\Foundation\Application as Laravel;
use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Optimole\Laravel\Routing\UrlGenerator;
use Optimole\Sdk\Optimole;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap package services.
     */
    public function boot()
    {
        $this->registerHelpers();
        $this->registerPublishing();
    }

    /**
     * Register package services.
     */
    public function register()
    {
        $this->bindUrlGenerator();
        $this->configure();
    }

    private function bindUrlGenerator()
    {
        $this->app->singleton(UrlGenerator::class, function (Laravel $app) {
            return new UrlGenerator($app['router']->getRoutes(), $app->make('request'));
        });

        $this->app->singleton(LaravelUrlGenerator::class, function (Laravel $app) {
            return $app->make(UrlGenerator::class);
        });

        $this->app->singleton('url', function (Laravel $app) {
            return $app->make(UrlGenerator::class);
        });

        $this->app->bind(UrlGeneratorContract::class, UrlGenerator::class);
    }

    /**
     * Configure the package.
     */
    private function configure()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/optimole.php', 'optimole');

        $key = config('optimole.key');

        if (!is_string($key)) {
            return;
        }

        Optimole::init($key, [
            'base_domain' => config('optimole.base_domain'),
            'cache_buster' => config('optimole.cache_buster'),
        ]);
    }

    /**
     * Register the package's helper functions.
     */
    private function registerHelpers()
    {
        require_once __DIR__.'/helpers.php';
    }

    /**
     * Register the package's publishable resources.
     */
    private function registerPublishing()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/optimole.php' => config_path('optimole.php'),
        ], 'optimole-config');
    }
}
