<?php

declare(strict_types=1);

namespace GildedRose\Domains\GlidedRose\ValueObjects;

use GildedRose\Domains\GlidedRose\Exceptions\InvalidStrategyClassException;
use GildedRose\Domains\GlidedRose\Interfaces\UpdateItemStrategyInterface;

/**
 * Class StrategyMapValueObject
 *
 * @package GildedRoseService\Domains\GlidedRose\ValueObjects
 */
class StrategyMapValueObject
{
    public array $args;

    public function __construct(
        // @phpstan-ignore-line
        public string $strategyClass,
        ...$args
    ) {
        $isStrategyClass = is_subclass_of($strategyClass, UpdateItemStrategyInterface::class);

        if (! $isStrategyClass) {
            throw new InvalidStrategyClassException(
                "Class {$strategyClass} does not implement " . UpdateItemStrategyInterface::class
            );
        }

        $this->args = $args;
    }

    public function getInstanceOfStrategy(): UpdateItemStrategyInterface
    {
        $stategy = new $this->strategyClass(...$this->args);
        if (! $stategy instanceof UpdateItemStrategyInterface) {
            throw new InvalidStrategyClassException(
                "Class {$this->strategyClass} does not implement UpdateItemStrategyInterface"
            );
        }

        return $stategy;
    }
}
