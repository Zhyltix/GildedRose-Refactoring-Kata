<?php

declare(strict_types=1);

namespace GildedRose\Domains\GlidedRose\UpdateStrategies;

use GildedRose\Domains\GlidedRose\Enums\UpdateItemEnum;
use GildedRose\Domains\GlidedRose\Exceptions\StrategyException;
use GildedRose\Domains\GlidedRose\Interfaces\UpdateItemStrategyInterface;
use GildedRose\Domains\GlidedRose\ValueObjects\ItemValueObject;

/**
 * Class UpdateQualityStrategy
 *
 * @package GildedRoseService\Domains\GlidedRose\UpdateStrategies
 */
class UpdateQualityStrategy implements UpdateItemStrategyInterface
{
    public function __construct(
        private UpdateItemEnum $updateType,
        private int $rate,
        private int $rateAfterSellIn,
        private int $maxQuality
    ) {
    }

    public function updateItem(ItemValueObject $item): bool
    {
        // Quality cannot be more than max quality
        $quality = &$item->quality;
        if ($quality >= $this->maxQuality) {
            return true;
        }

        switch ($this->updateType) {
            case UpdateItemEnum::INCREASE_QUALITY:
                return $this->handleIncreaseQuality($item);
            case UpdateItemEnum::DECREASE_QUALITY:
                return $this->handleDecreaseQuality($item);
            default:
                throw new StrategyException('Invalid update type');
        }
    }

    /**
     * Increase quality of the item
     */
    private function handleIncreaseQuality(ItemValueObject $item): bool
    {
        $quality = &$item->quality;
        $sellIn = $item->sellIn;
        $rate = $sellIn < 0 ? $this->rateAfterSellIn : $this->rate;

        // Quality cannot be more than max quality
        if ($quality >= $this->maxQuality) {
            return true;
        }

        $quality += $rate;

        return true;
    }

    /**
     * Decrease quality of the item
     */
    private function handleDecreaseQuality(ItemValueObject $item): bool
    {
        $quality = &$item->quality;
        $sellIn = $item->sellIn;
        $rate = $sellIn < 0 ? $this->rateAfterSellIn : $this->rate;

        // Quality cannot be negative
        if ($quality <= 0) {
            return true;
        }

        $quality -= $rate;

        return true;
    }
}
