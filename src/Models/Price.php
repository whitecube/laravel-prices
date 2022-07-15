<?php

namespace Whitecube\LaravelPrices\Models;

use DateTime;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Whitecube\Price\Price as PriceObject;
use Whitecube\LaravelPrices\Concerns\HasUuid;
use Whitecube\LaravelPrices\Exceptions\PriceValueNotDefinedException;

class Price extends Model
{
    use HasUuid;

    public $timestamps = ['activated_at'];

    protected $guarded = [];

    protected $casts = [
        'amount' => 'integer'
    ];

    public function __construct(
        array $arguments = null,
        int|string|float $amount = null,
        int $minor = null,
        string $currency = null,
        string $type = null,
        DateTime $activated_at = null
    ) {
        if (! is_null($arguments) && $arguments !== []) {
            return parent::__construct($arguments);
        }

        parent::__construct([
            'minor' => $minor,
            'amount' => $amount,
            'currency' => $currency,
            'type' => $type,
            'activated_at' => $activated_at
        ]);
    }

    public function fill(array $attributes)
    {
        if ($minor = $attributes['minor'] ?? null) {
            $attributes['amount'] = $minor;
        } else if (($amount = $attributes['amount'] ?? null) && ($currency = $attributes['currency'] ?? null)) {
            $attributes['amount'] = Money::of($amount, $currency)->getMinorAmount()->toInt();
        }

        unset($attributes['minor']);

        return parent::fill($attributes);
    }

    public function priceable()
    {
        return $this->morphTo();
    }

    public function scopeCurrent($query)
    {
        return $this->scopeEffectiveAt($query, now());
    }

    public function scopeEffectiveAt($query, DateTime $date)
    {
        return $query->whereNotNull('activated_at')->latest('activated_at')->where('activated_at', '<=', $date);
    }

    public function scopeOneOffs($query)
    {
        return $query->latest()->whereNull('activated_at');
    }

    public function toObject(): PriceObject
    {
        return PriceObject::ofMinor($this->amount, $this->currency);
    }

    public function getStatusAttribute()
    {
        if($this->activated_at > now()) {
            return 'programmed';
        }

        $current = $this->priceable->price()->first();
        if($current->id == $this->id) {
            return 'current';
        }

        return 'passed';
    }
}
