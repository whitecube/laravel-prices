<?php

namespace Whitecube\LaravelPrices;

use Illuminate\Support\ServiceProvider;

class LaravelPricesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishConfig();
        }

        if (!file_exists(config_path('prices.php'))) {
            $this->mergeConfigFrom($this->getConfigPath(), 'prices');
        }
    }

    private function publishConfig()
    {
        $path = $this->getConfigPath();
        $this->publishes([$path => config_path('prices.php')], 'prices-config');
    }

    private function getConfigPath()
    {
        return __DIR__ . '/../config/prices.php';
    }
}
