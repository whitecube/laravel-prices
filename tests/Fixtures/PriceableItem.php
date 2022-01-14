<?php

namespace Whitecube\LaravelPrices\Tests\Fixtures;

use Whitecube\LaravelPrices\HasPrices;
use Illuminate\Database\Eloquent\Model;

class PriceableItem extends Model
{
    use HasPrices;

    protected $guarded = [];
}
