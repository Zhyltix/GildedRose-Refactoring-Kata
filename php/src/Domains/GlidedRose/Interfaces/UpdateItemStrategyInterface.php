<?php

declare(strict_types=1);

namespace GildedRose\Domains\GlidedRose\Interfaces;

use GildedRose\Domains\GlidedRose\ValueObjects\ItemValueObject;

/**
 * Interface UpdateItemStrategyInterface
 *
 * @package GildedRoseService\Domains\GlidedRose\Interfaces
 */
interface UpdateItemStrategyInterface
{
    /**
     * Update item with strategy. Return true if successful and the item may go to the next strategy if it exists.
     */
    public function updateItem(ItemValueObject $item): bool;
}
