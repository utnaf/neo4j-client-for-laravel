<?php

namespace App\Providers;

use App\Repository\BeerRepository;
use App\Repository\BeerRepositoryInterface;
use App\Repository\RecommendationRepository;
use App\Repository\RecommendationRepositoryInterface;
use App\Repository\ReviewRepository;
use App\Repository\ReviewRepositoryInterface;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(BeerRepositoryInterface::class, BeerRepository::class);
        $this->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);
        $this->app->bind(RecommendationRepositoryInterface::class, RecommendationRepository::class);
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
