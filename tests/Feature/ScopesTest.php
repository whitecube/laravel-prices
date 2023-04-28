<?php

use Whitecube\LaravelPrices\Models\Price;
use Whitecube\LaravelPrices\Tests\Fixtures\PriceableItem;

beforeEach(function () {
    config(['price.model' => Price::class]);
});

test('the current scope can return the correct price', function() {
    $priceable_item = new PriceableItem(['id' => 1234]);
    $className = config('price.model');

    $priceable_item->setPrice(
        type: 'selling',
        amount: 123,
        currency: 'EUR',
        activated_at: now()->subWeeks(2)
    );

    $priceable_item->setPrice(
        type: 'selling',
        amount: 456,
        currency: 'EUR',
        activated_at: now()->subWeek()
    );

    $priceable_item->setPrice(
        type: 'selling',
        amount: 789,
        currency: 'EUR',
        activated_at: now()->addWeek()
    );

    $price = $priceable_item->prices()->current()->first();

    $this->assertNotNull($price);
    $this->assertInstanceOf($className, $price);
    $this->assertSame((string) $priceable_item->id, $price->priceable_id);
    $this->assertSame(45600, $price->amount);
    $this->assertNotNull($price->activated_at);
});

test('the effectiveAt scope can return the correct price', function() {
    $className = config('price.model');
    $priceable_item = new PriceableItem(['id' => 1234]);

    $priceable_item->setPrice(
        type: 'selling',
        amount: 123,
        currency: 'EUR',
        activated_at: now()->subWeeks(2)
    );

    $priceable_item->setPrice(
        type: 'selling',
        amount: 456,
        currency: 'EUR',
        activated_at: now()->subWeek()
    );

    $priceable_item->setPrice(
        type: 'selling',
        amount: 789,
        currency: 'EUR',
        activated_at: now()->addWeek()
    );

    // Two weeks ago
    $price = $priceable_item->prices()->effectiveAt(now()->subDays(9))->first();

    $this->assertNotNull($price);
    $this->assertInstanceOf($className, $price);
    $this->assertSame((string) $priceable_item->id, $price->priceable_id);
    $this->assertSame(12300, $price->amount);
    $this->assertNotNull($price->activated_at);


    // Last week
    $price = $priceable_item->prices()->effectiveAt(now()->subDays(6))->first();

    $this->assertNotNull($price);
    $this->assertInstanceOf($className, $price);
    $this->assertSame((string) $priceable_item->id, $price->priceable_id);
    $this->assertSame(45600, $price->amount);
    $this->assertNotNull($price->activated_at);


    // In two weeks
    $price = $priceable_item->prices()->effectiveAt(now()->addWeeks(2))->first();

    $this->assertNotNull($price);
    $this->assertInstanceOf($className, $price);
    $this->assertSame((string) $priceable_item->id, $price->priceable_id);
    $this->assertSame(78900, $price->amount);
    $this->assertNotNull($price->activated_at);
});

test('prices with activated_at = null are not considered when using time-based scopes', function() {
    $className = config('price.model');
    $priceable_item = new PriceableItem(['id' => 1234]);

    $priceable_item->setPrice(
        type: 'selling',
        amount: 123,
        currency: 'EUR',
        activated_at: now()->subWeeks(2)
    );

    $priceable_item->setPrice(
        type: 'selling',
        amount: 456,
        currency: 'EUR',
        activated_at: null
    );

    $priceable_item->setPrice(
        type: 'selling',
        amount: 789,
        currency: 'EUR',
        activated_at: now()->addWeek()
    );

    // Two weeks ago
    $price = $priceable_item->prices()->effectiveAt(now()->subDays(9))->first();

    $this->assertNotNull($price);
    $this->assertInstanceOf($className, $price);
    $this->assertSame((string) $priceable_item->id, $price->priceable_id);
    $this->assertSame(12300, $price->amount);
    $this->assertNotNull($price->activated_at);


    // Now
    $price = $priceable_item->prices()->current()->first();

    $this->assertNotNull($price);
    $this->assertInstanceOf($className, $price);
    $this->assertSame((string) $priceable_item->id, $price->priceable_id);
    $this->assertSame(12300, $price->amount);
    $this->assertNotNull($price->activated_at);


    // In two weeks
    $price = $priceable_item->prices()->effectiveAt(now()->addWeeks(2))->first();

    $this->assertNotNull($price);
    $this->assertInstanceOf($className, $price);
    $this->assertSame((string) $priceable_item->id, $price->priceable_id);
    $this->assertSame(78900, $price->amount);
    $this->assertNotNull($price->activated_at);
});

test('the oneOffs scope returns the correct prices', function() {
    $className = config('price.model');
    $priceable_item = new PriceableItem(['id' => 1234]);

    $priceable_item->price = new $className([
        'type' => 'selling',
        'amount' => 123,
        'currency' => 'EUR',
        'created_at' => now()->subWeek() // specify created at date in the past to predetermine order_by
    ]);

    $priceable_item->setPrice(
        type: 'selling',
        amount: 456,
        currency: 'EUR',
        activated_at: now()->subWeeks(2)
    );

    $priceable_item->setPrice(
        type: 'selling',
        amount: 789,
        currency: 'EUR'
    );

    $prices = $priceable_item->prices()->oneOffs()->get();

    $this->assertCount(2, $prices);
    $this->assertEquals(78900, $prices[0]->amount);
    $this->assertEquals(12300, $prices[1]->amount);
});
