<?php

declare(strict_types=1);

namespace Tests;

use ApprovalTests\Approvals;
use GildedRose\Domains\GlidedRose\Services\GildedRoseService;
use GildedRose\Domains\GlidedRose\ValueObjects\ItemValueObject;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new ItemValueObject('foo', 0, 0)];

        $gildedRose = new GildedRoseService($items);

        $gildedRose->updateQuality();

        $this->assertSame('foo', $items[0]->name);
        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(0, $items[0]->quality);
    }

    public function testSellInZero(): void
    {
        $items = [new ItemValueObject('foo', 0, 10)];

        $gildedRose = new GildedRoseService($items);

        $gildedRose->updateQuality();

        $this->assertSame('foo', $items[0]->name);
        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(8, $items[0]->quality);
    }

    public function testQualityZero(): void
    {
        $items = [new ItemValueObject('foo', 10, 0)];

        $gildedRose = new GildedRoseService($items);

        $gildedRose->updateQuality();

        $this->assertSame('foo', $items[0]->name);
        $this->assertSame(9, $items[0]->sellIn);
        $this->assertSame(0, $items[0]->quality);
    }

    public function testQualityNegative(): void
    {
        $items = [new ItemValueObject('foo', 10, -1)];

        $gildedRose = new GildedRoseService($items);

        $gildedRose->updateQuality();

        $this->assertSame('foo', $items[0]->name);
        $this->assertSame(9, $items[0]->sellIn);
        $this->assertSame(-1, $items[0]->quality);
    }

    public function testAgedBrie(): void
    {
        $items = [new ItemValueObject('Aged Brie', 2, 0)];

        $gildedRose = new GildedRoseService($items);

        $gildedRose->updateQuality();

        $this->assertSame('Aged Brie', $items[0]->name);
        $this->assertSame(1, $items[0]->sellIn);
        $this->assertSame(1, $items[0]->quality);
    }

    public function testSulfuras(): void
    {
        $items = [new ItemValueObject('Sulfuras, Hand of Ragnaros', 0, 80)];

        $gildedRose = new GildedRoseService($items);

        $gildedRose->updateQuality();

        $this->assertSame('Sulfuras, Hand of Ragnaros', $items[0]->name);
        $this->assertSame(0, $items[0]->sellIn);
        $this->assertSame(80, $items[0]->quality);
    }

    public function testBackstagePasses(): void
    {
        $items = [new ItemValueObject('Backstage passes to a TAFKAL80ETC concert', 12, 20)];

        $gildedRose = new GildedRoseService($items);

        $gildedRose->updateQuality();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(11, $items[0]->sellIn);
        $this->assertSame(21, $items[0]->quality);

        $gildedRose->updateQuality();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(10, $items[0]->sellIn);
        $this->assertSame(22, $items[0]->quality);

        $gildedRose->updateQuality();

        $items = [new ItemValueObject('Backstage passes to a TAFKAL80ETC concert', 5, 20)];

        $gildedRose = new GildedRoseService($items);

        $gildedRose->updateQuality();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(4, $items[0]->sellIn);
        $this->assertSame(23, $items[0]->quality);

        $gildedRose->updateQuality();
        $gildedRose->updateQuality();
        $gildedRose->updateQuality();
        $gildedRose->updateQuality();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(0, $items[0]->sellIn);
        $this->assertSame(35, $items[0]->quality);

        $gildedRose->updateQuality();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(-1, $items[0]->sellIn);
        $this->assertSame(0, $items[0]->quality);

        $items = [new ItemValueObject('Backstage passes to a TAFKAL80ETC concert', 15, 49)];

        $gildedRose = new GildedRoseService($items);

        $gildedRose->updateQuality();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(14, $items[0]->sellIn);
        $this->assertSame(50, $items[0]->quality);

        $gildedRose->updateQuality();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(13, $items[0]->sellIn);
        $this->assertSame(50, $items[0]->quality);
    }

    public function testConjured(): void
    {
        $items = [new ItemValueObject('Conjured Mana Cake', 3, 6)];

        $gildedRose = new GildedRoseService($items);

        $gildedRose->updateQuality();

        $this->assertSame('Conjured Mana Cake', $items[0]->name);
        $this->assertSame(2, $items[0]->sellIn);
        $this->assertSame(4, $items[0]->quality);
    }

    public function testThirtyDays(): void
    {
        ob_start();

        $argv = ['', 30];
        include(__DIR__ . '/../fixtures/texttest_fixture.php');

        $output = ob_get_clean();

        Approvals::approveString($output);
    }
}
