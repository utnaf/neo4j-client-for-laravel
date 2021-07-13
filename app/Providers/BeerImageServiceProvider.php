<?php

namespace App\Providers;

use App\Service\BeerImageService;
use Illuminate\Support\ServiceProvider;

class BeerImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BeerImageService::class, function($app) {
            return new BeerImageService('public/images/beers');
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
