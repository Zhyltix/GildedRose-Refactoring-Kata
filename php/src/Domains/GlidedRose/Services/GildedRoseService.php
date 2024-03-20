<?php

declare(strict_types=1);

namespace GildedRose\Domains\GlidedRose\Services;

use GildedRose\Domains\GlidedRose\Factories\StrategyFactory;
use GildedRose\Domains\GlidedRose\Interfaces\StaticSellInItemInterface;
use GildedRose\Domains\GlidedRose\ValueObjects\ItemValueObject;

final class GildedRoseService
{
    /**
     * @param ItemValueObject[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        $strategyFactory = new StrategyFactory();

        foreach ($this->items as $item) {
            // get the strategies for the item
            $strategies = $strategyFactory->getStrategiesForItem($item);

            // check if the item is a static sellInItem and if not reduce one day from the sellIn
            $isStaticSellInItem = count(
                array_filter(
                    $strategies,
                    fn ($strategy) => $strategy instanceof StaticSellInItemInterface
                )
            ) > 0;
            if (! $isStaticSellInItem) {
                $item->sellIn = $item->sellIn - 1;
            }

            // update the item with the strategies
            foreach ($strategies as $strategy) {
                $continueStrategies = $strategy->updateItem($item);
                if (! $continueStrategies) {
                    break;
                }
            }
        }

        return;
    }
}
