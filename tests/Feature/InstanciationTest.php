<?php

use Whitecube\LaravelPrices\Models\Price;

beforeEach(function () {
    config(['price.model' => Price::class]);
});

test('a price instance can be created with a constructor arguments array', function() {
    $className = config('price.model');
    $price = new $className([
        'type' => 'selling',
        'amount' => 123,
        'currency' => 'EUR',
        'activated_at' => now()->addWeek()
    ]);

    $this->assertNotNull($price);
    $this->assertInstanceOf($className, $price);
    $this->assertSame('selling', $price->type);
    $this->assertSame(12300, $price->amount);
    $this->assertSame('EUR', $price->currency);
});

test('a price instance can be created with named constructor arguments', function() {
    $className = config('price.model');

    $price = new $className(
        type: 'selling',
        amount: 123,
        currency: 'EUR',
        activated_at: now()->addWeek()
    );

    $this->assertNotNull($price);
    $this->assertInstanceOf($className, $price);
    $this->assertSame('selling', $price->type);
    $this->assertSame(12300, $price->amount);
    $this->assertSame('EUR', $price->currency);
});

test('a price instance can be created from a minor value using a constructor arguments array', function() {
    $className = config('price.model');
    $price = new $className([
        'type' => 'selling',
        'minor' => 123,
        'currency' => 'EUR',
        'activated_at' => now()->addWeek()
    ]);

    $this->assertNotNull($price);
    $this->assertInstanceOf(Price::class, $price);
    $this->assertSame('selling', $price->type);
    $this->assertSame(123, $price->amount);
    $this->assertSame('EUR', $price->currency);
});

test('a price instance can be created from a minor value using named constructor arguments', function() {
    $className = config('price.model');
    $price = new $className(
        type: 'selling',
        minor: 123,
        currency: 'EUR',
        activated_at: now()->addWeek()
    );

    $this->assertNotNull($price);
    $this->assertInstanceOf($className, $price);
    $this->assertSame('selling', $price->type);
    $this->assertSame(123, $price->amount);
    $this->assertSame('EUR', $price->currency);
});
