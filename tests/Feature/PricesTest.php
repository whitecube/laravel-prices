<?php

use Whitecube\Price\Price as PhpPrice;
use Whitecube\LaravelPrices\Models\Price;
use Whitecube\LaravelPrices\Tests\Fixtures\PriceableItem;

test('a price can be set on a priceable item via the relationship', function() {
    $priceable_item = new PriceableItem(['id' => '1234']);

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

test('a price can be set on a priceable item via the setter', function() {
    $priceable_item = new PriceableItem(['id' => 1234]);

    $price = new Price([
        'type' => 'selling',
        'amount' => 123,
        'currency' => 'EUR',
        'activated_at' => now()->subWeek()
    ]);

    $priceable_item->price = $price;

    $price = $priceable_item->prices()->current()->first();

    $this->assertNotNull($price);
    $this->assertInstanceOf(Price::class, $price);
    $this->assertSame((string) $priceable_item->id, $price->priceable_id);
    $this->assertSame(PriceableItem::class, $price->priceable_type);
    $this->assertSame('selling', $price->type);
    $this->assertSame(12300, $price->amount);
    $this->assertSame('EUR', $price->currency);
    $this->assertNotNull($price->activated_at);
    $this->assertNotNull($price->created_at);
    $this->assertNotNull($price->updated_at);
});

test('a price can be set on a priceable item via the setPrice method', function() {
    $priceable_item = new PriceableItem(['id' => 1234]);

    $priceable_item->setPrice(
        type: 'selling',
        amount: 123,
        currency: 'EUR',
        activated_at: now()->subWeek()
    );

    $price = $priceable_item->prices()->current()->first();

    $this->assertNotNull($price);
    $this->assertInstanceOf(Price::class, $price);
    $this->assertSame((string) $priceable_item->id, $price->priceable_id);
    $this->assertSame(PriceableItem::class, $price->priceable_type);
    $this->assertSame('selling', $price->type);
    $this->assertSame(12300, $price->amount);
    $this->assertSame('EUR', $price->currency);
    $this->assertNotNull($price->activated_at);
    $this->assertNotNull($price->created_at);
    $this->assertNotNull($price->updated_at);
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
    $this->assertEquals(12300, $php_price->toMinor());
});

test('accessing the price through the accessor returns a whitecube/php-prices instance of the latest active price', function() {
    $priceable_item = new PriceableItem(['id' => '1234']);

    $priceable_item = $priceable_item->setPrice(
        type: 'selling',
        amount: 123,
        currency: 'EUR',
        activated_at: now()->subWeek()
    );

    $php_price = $priceable_item->price;

    $this->assertInstanceOf(PhpPrice::class, $php_price);
    $this->assertInstanceOf(PriceableItem::class, $priceable_item);
    $this->assertEquals(12300, $php_price->toMinor());
});
