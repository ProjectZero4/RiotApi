<?php


namespace App\packages\ProjectZero4\RiotApi;


use Illuminate\Support\ServiceProvider;
use ProjectZero4\RiotApi\RiotApi;
use function ProjectZero4\RiotApi\riotApiRoot;

class RiotApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\Champions::class,
            ]);
        }

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->app->singleton(RiotApi::class, function ($app) {
            return new RiotApi('euw1');
        });
        $this->publishes([
            __DIR__ . "/public" => public_path(riotApiRoot()),
        ], 'public');
    }
}
