<?php


namespace ProjectZero4\RiotApi;


use Illuminate\Support\ServiceProvider;

class RiotApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            Commands\Champions::class,
            Commands\Maps::class,
            Commands\Queues::class,
            Commands\Games::class,
            Commands\Fresh::class,
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->app->singleton(RiotApi::class, function ($app) {
            return new RiotApi('euw1');
        });
        $this->publishes([
            __DIR__ . "/public" => public_path(riotApiRoot()),
        ], 'public');
    }
}
