<?php

declare(strict_types=1);

namespace GildedRose\Domains\GlidedRose\UpdateStrategies;

use GildedRose\Domains\GlidedRose\Interfaces\UpdateItemStrategyInterface;
use GildedRose\Domains\GlidedRose\ValueObjects\ItemValueObject;

/**
 * Class IncreaseWithDeadlines
 *
 * @package GildedRose\Domains\GlidedRose\UpdateStrategies
 */
class IncreaseWithDeadlines implements UpdateItemStrategyInterface
{
    public function __construct(
        private int $increaseRate,
        private int $maxQuality,
        private array $deadlines
    ) {
        usort($this->deadlines, fn ($a, $b) => $a->sellInDeadline <=> $b->sellInDeadline);
    }

    public function updateItem(ItemValueObject $item): bool
    {
        $quality = &$item->quality;

        // if the deadline is reached update the quality and return
        if ($this->handleDeadlines($item)) {
            return true;
        }

        // if no deadline is reached increase the quality with the default rate
        $quality += $this->increaseRate;

        if ($quality > $this->maxQuality) {
            $quality = $this->maxQuality;
        }

        return true;
    }

    /**
     * Handle deadlines
     */
    private function handleDeadlines(ItemValueObject $item): bool
    {
        $quality = &$item->quality;
        $sellIn = $item->sellIn;

        // go through the deadlines and check if the sellIn is less than the deadline and if so update the quality
        foreach ($this->deadlines as $deadline) {
            // if the deadline is reached and the sellIn is less than the deadline set the quality to 0
            if ($sellIn < $deadline->sellInDeadline && $deadline->deadlineReached) {
                $quality = 0;

                return true;
            }

            if ($sellIn < $deadline->sellInDeadline) {
                $quality += $deadline->qualityIncreaseRate;

                if ($quality > $this->maxQuality) {
                    $quality = $this->maxQuality;
                }

                return true;
            }
        }

        return false;
    }
}
