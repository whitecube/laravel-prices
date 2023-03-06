<?php

namespace Whitecube\LaravelPrices\Enums;

enum PriceStatus
{
    case SCHEDULED;
    case CURRENT;
    case EXPIRED;
}