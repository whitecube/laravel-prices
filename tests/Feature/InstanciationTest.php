<?php

use Whitecube\LaravelPrices\Models\Price;

test('a price instance can be created with a constructor arguments array', function() {
    $price = new Price([
        'type' => 'selling',
        'amount' => 123,
        'currency' => 'EUR',
        'activated_at' => now()->addWeek()
    ]);

    $this->assertNotNull($price);
    $this->assertInstanceOf(Price::class, $price);
    $this->assertSame('selling', $price->type);
    $this->assertSame(12300, $price->amount);
    $this->assertSame('EUR', $price->currency);
});

test('a price instance can be created with named constructor arguments', function() {
    $price = new Price(
        type: 'selling',
        amount: 123,
        currency: 'EUR',
        activated_at: now()->addWeek()
    );

    $this->assertNotNull($price);
    $this->assertInstanceOf(Price::class, $price);
    $this->assertSame('selling', $price->type);
    $this->assertSame(12300, $price->amount);
    $this->assertSame('EUR', $price->currency);
});

test('a price instance can be created from a minor value using a constructor arguments array', function() {
    $price = new Price([
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
    $price = new Price(
        type: 'selling',
        minor: 123,
        currency: 'EUR',
        activated_at: now()->addWeek()
    );

    $this->assertNotNull($price);
    $this->assertInstanceOf(Price::class, $price);
    $this->assertSame('selling', $price->type);
    $this->assertSame(123, $price->amount);
    $this->assertSame('EUR', $price->currency);
});
