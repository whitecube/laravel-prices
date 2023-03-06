<?php

namespace Whitecube\LaravelPrices\Enums;

enum PriceStatus: string
{
    case SCHEDULED = 'scheduled';
    case CURRENT = 'current';
    case EXPIRED = 'expired';
}