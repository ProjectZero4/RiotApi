<?php


namespace App\packages\ProjectZero4\RiotApi;


use Illuminate\Support\ServiceProvider;
use ProjectZero4\RiotApi\RiotApi;

class RiotApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->app->singleton(RiotApi::class, function ($app) {
            return new RiotApi('euw1');
        });
    }

    public function handle()
    {

    }
}
