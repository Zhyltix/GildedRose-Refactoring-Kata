<?php

declare(strict_types=1);

namespace GildedRose\Domains\GlidedRose\Factories;

use GildedRose\Domains\GlidedRose\Enums\UpdateItemEnum;
use GildedRose\Domains\GlidedRose\Exceptions\StrategyException;
use GildedRose\Domains\GlidedRose\UpdateStrategies\IncreaseWithDeadlines;
use GildedRose\Domains\GlidedRose\UpdateStrategies\StaticQualityStrategy;
use GildedRose\Domains\GlidedRose\UpdateStrategies\UpdateQualityStrategy;
use GildedRose\Domains\GlidedRose\ValueObjects\ItemValueObject;
use GildedRose\Domains\GlidedRose\ValueObjects\SellInDeadlineValueObject;
use GildedRose\Domains\GlidedRose\ValueObjects\StrategyMapValueObject;

/**
 * Class StrategyFactory
 *
 * @package GildedRoseService\Domains\GlidedRose\Factories
 */
class StrategyFactory
{
    /**
     * @var array|null
     */
    private static $strategyMap = null;

    public function getStrategiesForItem(ItemValueObject $item): array
    {
        $stategies = $this->getBaseStrategies();

        // find specific strategies for the item if available
        $matchingStrategies = array_filter(
            $this->getStrategyMap(),
            fn ($key) => mb_strpos($item->name, $key) !== false,
            ARRAY_FILTER_USE_KEY
        );

        if (count($matchingStrategies) > 1) {
            throw new StrategyException('Multiple strategie maps found for item');
        }

        if (count($matchingStrategies) === 1) {
            $stategies = array_values($matchingStrategies)[0];
        }

        return array_map(
            fn (StrategyMapValueObject $strategyMap) => $strategyMap->getInstanceOfStrategy(),
            $stategies
        );
    }

    /**
     * get strategy map for specific items and their strategies
     */
    private function getStrategyMap(): array
    {
        if (self::$strategyMap !== null) {
            return self::$strategyMap;
        }

        self::$strategyMap = [
            'Aged Brie' => [
                new StrategyMapValueObject(
                    UpdateQualityStrategy::class,
                    UpdateItemEnum::INCREASE_QUALITY,
                    1,
                    2,
                    50
                ),
            ],
            'Sulfuras' => [
                new StrategyMapValueObject(StaticQualityStrategy::class),
            ],
            'Backstage passes' => [
                new StrategyMapValueObject(
                    IncreaseWithDeadlines::class,
                    1,
                    50,
                    [
                        new SellInDeadlineValueObject(0, 0, true),
                        new SellInDeadlineValueObject(5, 3),
                        new SellInDeadlineValueObject(10, 2),
                    ]
                ),
            ],
            'Conjured' => [
                new StrategyMapValueObject(
                    UpdateQualityStrategy::class,
                    UpdateItemEnum::DECREASE_QUALITY,
                    2,
                    4,
                    50
                ),
            ],
        ];

        return self::$strategyMap;
    }

    /**
     * base strategy if no specific strategies are found
     */
    private function getBaseStrategies(): array
    {
        return [
            new StrategyMapValueObject(
                UpdateQualityStrategy::class,
                UpdateItemEnum::DECREASE_QUALITY,
                1,
                2,
                50
            ),
        ];
    }
}
