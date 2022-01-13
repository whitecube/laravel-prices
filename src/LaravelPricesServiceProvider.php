<?php

namespace Whitecube\LaravelPrices;

use Illuminate\Support\ServiceProvider;

class LaravelPricesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
