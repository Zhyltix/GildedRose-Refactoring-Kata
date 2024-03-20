<?php

declare(strict_types=1);

namespace GildedRose\Domains\GlidedRose\Enums;

/**
 * Class UpdateItemEnum
 *
 * @package GildedRose\Domains\GlidedRose\Enums
 */
enum UpdateItemEnum: string
{
    case INCREASE_QUALITY = 'increase_quality';
    case DECREASE_QUALITY = 'decrease_quality';
}
