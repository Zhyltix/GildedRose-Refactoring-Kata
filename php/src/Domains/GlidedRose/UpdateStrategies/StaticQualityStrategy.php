<?php

declare(strict_types=1);

namespace GildedRose\Domains\GlidedRose\UpdateStrategies;

use GildedRose\Domains\GlidedRose\Interfaces\StaticSellInItemInterface;
use GildedRose\Domains\GlidedRose\Interfaces\UpdateItemStrategyInterface;
use GildedRose\Domains\GlidedRose\ValueObjects\ItemValueObject;

/**
 * Class StaticQualityStrategy
 *
 * @package GildedRose\Domains\GlidedRose\UpdateStrategies
 */
class StaticQualityStrategy implements UpdateItemStrategyInterface, StaticSellInItemInterface
{
    public function updateItem(ItemValueObject $item): bool
    {
        return false;
    }
}
