# laravel-prices
Manage acquisition, selling &amp; renting prices for products and services in your Laravel Application

## Installation

```shell
composer require whitecube/laravel-prices
```

## Configuration

You can publish the config file by running this command :

```shell
php artisan vendor:publish --tag=prices-config 
```

Once your configuration file is created in `config/prices.php`, you can edit the Price model to a custom Price model by changing:

```php
return [
    // …
    'model' => \App\Models\CustomPriceModel::class,
];
```

## Quick overview

This package lets you attach prices to anything you want, and keeps a history of the price changes overtime.

To achieve that, the package will migrate a `prices` table which is used to store every price change that occurs on your priceable items _(such as products or services)_. When you access your item's price, the most recent result in that table will be returned.

This documentation will use a fake `Product` model as an example, however you can link these prices to anything you want.
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
use Whitecube\LaravelPrices\Models\Price;

$product->price = new Price(
    amount: 50, 
    currency: 'EUR', 
    type: 'selling', 
    activated_at: now()->addWeek()
);
```
or if you want to avoid the `use` statement

```php
$product->setPrice(
    amount: 50, 
    currency: 'EUR', 
    type: 'selling', 
    activated_at: now()->addWeek()
);
```

It's important to note that this package uses [`whitecube/php-prices`](https://github.com/whitecube/php-prices) under the hood. This allows you to later do accurate calculations with your prices, without running into problems with floating point number precision.

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

By default, this will return the latest price that has the `selling` type, but you can change that by overriding the `getDefaultPriceType` method on your class. It must return a string that corresponds to the price type you want to use by default for that priceable item class.

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

### How to handle one-off prices

The main way to use prices with this package is to use it as a chronological history table of all the prices for a specific item. This means you should define the `activated_at` attribute when creating the price so that the system can accurately make a decision on whether or not it should consider the price when you query it.

However, you may sometimes need to specify a special one-off price for an item, without it getting applied automatically every time. This can be done easily by leaving out or setting the `activated_at` attribute to null, and storing the price's ID where you need to have a reference to it. When you do so, these prices will never be returned when using time-based scopes (such as `current()` or `effectiveAt()`).

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

--- 

`oneOffs()`

Filters the query to only return the one-off prices (`activated_at === null`)
```php
$product->prices()->oneOffs()->get();
```

---

## 🔥 Sponsorships

If you are reliant on this package in your production applications, consider [sponsoring us](https://github.com/sponsors/whitecube)! It is the best way to help us keep doing what we love to do: making great open source software.

## Contributing

Feel free to suggest changes, ask for new features or fix bugs yourself. We're sure there are still a lot of improvements that could be made, and we would be very happy to merge useful pull requests.

Thanks!

## Made with ❤️ for open source

At [Whitecube](https://www.whitecube.be) we use a lot of open source software as part of our daily work.
So when we have an opportunity to give something back, we're super excited!

We hope you will enjoy this small contribution from us and would love to [hear from you](mailto:hello@whitecube.be) if you find it useful in your projects. Follow us on [Twitter](https://twitter.com/whitecube_be) for more updates!

