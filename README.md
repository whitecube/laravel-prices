# laravel-prices
Manage acquisition, selling &amp; renting prices for products and services in your Laravel Application

## Installation

```shell
composer require whitecube/laravel-prices
```

## Quick overview

This package lets you attach prices to anything you want, and keeps a history of the price changes overtime.

To achieve that, the package will migrate a `prices` table which is used to store every price change that occurs on your priceable items _(such as products or services)_. When you access your item's price, the most recent result in that table will be returned.

This documentation will use a fake `Product` model as an example, however the scope of this package is larger than that. 

## Usage
Add the `HasPrices` trait to your Product model.

```php
<?php

use Whitecube\LaravelPrices\HasPrices;

class Product extends Model
{
    use HasPrices;
}
```

This unlocks the whole functionality of this package for that model. Here are the things you can now do:


### Setting a price

```php
$product->price = new Price(amount: 50, currency: 'EUR');
```

You can pass additional arguments, such as the type (defaults to selling, more on this later) and the moment the price needs to come into effect.

```php
$product->price = new Price(
  amount: 50, 
  currency: 'EUR', 
  type: 'selling', 
  activated_at: now()->addWeek()
);
```

It's important to note that this uses [`whitecube/php-prices`](https://github.com/whitecube/php-prices) under the hood. This allows you to later do accurate calculations with your prices, without running into problems with floating point number precision.

This means it converts the price into "minor units" (aka cents) before storage in the database. The value you specify when creating a new price can be either in major or minor units. To define a price directly in minor units, use the `minor` argument instead of `amount`:


```php
$product->price = new Price(
  minor: 5000, 
  currency: 'EUR', 
  type: 'selling', 
  activated_at: now()->addWeek()
);
```

### Getting the current selling price

The quickest and easiest way, getting a `Whitecube\Price\Price` instance so you're ready to do accurate calculations with it.
```php
$price = $product->price;
```

### Accessing the relationship manually

The above example does a little magic via an accessor method on the trait to make the most common use case easier, but behind the scenes it's a simple query on a relation. You can query this relation yourself when necessary (and with the help of the `current()` scope, it will only return the currently active price).

```php
$buying_price = $product->prices()->current()->where('type', 'buying')->first();
```

Do note that this returns an instance of the `Whitecube\LaravelPrices\Models\Price` model, not a `Whitecube\Price\Price` instance. 
To access that manually, call :

```php
$buying_price->toObject();
```

### Available scopes

`current()`

Filters the query to only return the current price model.

```php
$product->prices()->current()->first();
```

--- 

`effectiveAt($date)`

Filters the query to only return the price model that was active at the given time (accepts a Carbon instance)

```php
$product->prices()->effectiveAt(now()->subWeek())->first();
```



