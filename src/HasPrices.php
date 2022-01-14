<?php

namespace Whitecube\LaravelPrices;

use Whitecube\LaravelPrices\Models\Price;

trait HasPrices
{
    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }
}
