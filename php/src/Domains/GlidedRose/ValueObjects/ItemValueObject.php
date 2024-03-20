<?php

declare(strict_types=1);

namespace GildedRose\Domains\GlidedRose\ValueObjects;

/**
 * Class ItemValueObject
 *
 * @package GildedRose\Domains\GlidedRose\ValueObjects
 */
class ItemValueObject implements \Stringable
{
    public function __construct(
        public string $name,
        public int $sellIn,
        public int $quality
    ) {
    }

    public function __toString(): string
    {
        return (string) "{$this->name}, {$this->sellIn}, {$this->quality}";
    }
}
