<?php

declare(strict_types=1);

namespace GildedRose\Domains\GlidedRose\ValueObjects;

/**
 * Class SellInDeadlineValueObject
 *
 * @package GildedRose\Domains\GlidedRose\ValueObjects
 */
class SellInDeadlineValueObject
{
    public function __construct(
        public int $sellInDeadline,
        public int $qualityIncreaseRate,
        public bool $deadlineReached = false
    ) {
    }
}
