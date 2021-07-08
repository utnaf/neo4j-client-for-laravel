<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\ClientInterface;

class Neo4jServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ClientInterface::class, function ($app) {
            $driver = config('database.connections.neo4j.driver');
            return ClientBuilder::create()
                ->withDriver(
                    $driver,
                    config('database.connections.neo4j.url'),
                    Authenticate::basic(
                        config('database.connections.neo4j.username'),
                        config('database.connections.neo4j.password')
                    )
                )
                ->withDefaultDriver($driver)
                ->build();
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
