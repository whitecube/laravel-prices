<?php

use Whitecube\LaravelPrices\Models\Price;
use Whitecube\LaravelPrices\Tests\Fixtures\PriceableItem;

test('a price can be set on a priceable item', function() {
    $priceable_item = new PriceableItem(['id' => 1234]);

    $price = $priceable_item->prices()->create([
        'type' => 'selling',
        'amount' => 123,
        'currency' => 'EUR',
        'activated_at' => now()->addWeek()
    ]);

    $this->assertNotNull($price);
    $this->assertInstanceOf(Price::class, $price);
    $this->assertSame($priceable_item->id, $price->priceable_id);
    $this->assertSame(PriceableItem::class, $price->priceable_type);
    $this->assertSame('selling', $price->type);
    $this->assertSame(12300, $price->amount);
    $this->assertSame('EUR', $price->currency);
    $this->assertNotNull($price->activated_at);
    $this->assertNotNull($price->created_at);
    $this->assertNotNull($price->updated_at);
});

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

test('a price instance can return a whitecube/php-prices object', function() {
    $price = new Price(
        type: 'selling',
        amount: 123,
        currency: 'EUR',
        activated_at: now()->addWeek()
    );

    $php_price = $price->toObject();

    $this->assertInstanceOf(Whitecube\Price\Price::class, $php_price);
    $this->assertTrue($php_price->equals(12300));
});
