<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use GildedRose\Domains\GlidedRose\Services\GildedRoseService;
use GildedRose\Domains\GlidedRose\ValueObjects\ItemValueObject;

$items = [
    new ItemValueObject('+5 Dexterity Vest', 10, 20),
    new ItemValueObject('Aged Brie', 2, 0),
    new ItemValueObject('Elixir of the Mongoose', 5, 7),
    new ItemValueObject('Sulfuras, Hand of Ragnaros', 0, 80),
    new ItemValueObject('Sulfuras, Hand of Ragnaros', -1, 80),
    new ItemValueObject('Backstage passes to a TAFKAL80ETC concert', 15, 20),
    new ItemValueObject('Backstage passes to a TAFKAL80ETC concert', 10, 49),
    new ItemValueObject('Backstage passes to a TAFKAL80ETC concert', 5, 49),
    new ItemValueObject('Conjured Mana Cake', 3, 6),
];

$app = new GildedRoseService($items);

$days = 2;
if ((is_countable($argv) ? count($argv) : 0) > 1) { // @phpstan-ignore-line
    $days = (int) $argv[1];
}

for ($i = 0; $i < $days; $i++) {
    echo "-------- day {$i} --------" . PHP_EOL;
    echo 'name, sellIn, quality' . PHP_EOL;
    foreach ($items as $item) {
        echo $item . PHP_EOL;
    }
    echo PHP_EOL;
    $app->updateQuality();
}
