<?php

namespace Whitecube\LaravelPrices\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Whitecube\LaravelPrices\LaravelPricesServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelPricesServiceProvider::class,
        ];
    }
}
