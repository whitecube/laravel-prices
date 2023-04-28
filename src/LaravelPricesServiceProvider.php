<?php

namespace Whitecube\LaravelPrices;

use Illuminate\Support\ServiceProvider;

class LaravelPricesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            $this->getConfigPath() => config_path('prices.php')
        ], 'prices-config');

        if (! file_exists(config_path('prices.php'))) {
            $this->mergeConfigFrom($this->getConfigPath(), 'prices');
        }
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/prices.php';
    }
}
