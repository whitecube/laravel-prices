<?php

namespace Whitecube\LaravelPrices;

use DateTime;
use Illuminate\Database\Eloquent\Model;

trait HasPrices
{
    /**
     * The prices relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function prices()
    {
        return $this->morphMany(config('prices.model'), 'priceable');
    }

    /**
     * The current price model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function price()
    {
        return $this->morphOne(config('prices.model'), 'priceable')->where('type', $this->getDefaultPriceType())->current();
    }

    /**
     * Easy accessor to get the current price as a whitecube/php-prices object
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getPriceAttribute()
    {
        $this->loadMissing('price');

        return $this->getRelation('price')?->toObject();
    }

    /**
     * Set (attach) a new price to this item
     *
     * @param \Illuminate\Database\Eloquent\Model $price
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setPriceAttribute(Model $price): Model
    {
        return $this->prices()->save($price);
    }

    /**
     * Set (attach) a new price by manually passing the arguments
     *
     * @param array|null $arguments
     * @param integer|string|float|null $amount
     * @param integer|null $minor
     * @param string|null $currency
     * @param string|null $type
     * @param DateTime|null $activated_at
     * @return static
     */
    public function setPrice(
        array $arguments = null,
        int|string|float $amount = null,
        int $minor = null,
        string $currency = null,
        string $type = null,
        DateTime $activated_at = null
    ): static
    {
        $className= config('prices.model');
        $this->price = new $className($arguments, $amount, $minor, $currency, $type, $activated_at);

        return $this;
    }

    /**
     * Get the price, as a model or as a whitecube/php-prices instance
     *
     * @param boolean $asModel
     * @return \Whitecube\Price\Price|\Illuminate\Database\Eloquent\Model
     */
    public function getPrice(bool $asModel = false)
    {
        if (! $asModel) {
            return $this->price;
        }

        return $this->price()->first();
    }

    /**
     * Get the default price type
     *
     * @return string
     */
    protected function getDefaultPriceType(): string
    {
        return 'selling';
    }

}
