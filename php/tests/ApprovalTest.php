<?php

declare(strict_types=1);

namespace Tests;

use ApprovalTests\Approvals;
use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class ApprovalTest extends TestCase
{
    public function testTestFixture(): void
    {
        $received = 'OMGHAI!' . PHP_EOL;

        $items = [
            new Item('+5 Dexterity Vest', 10, 20),
            new Item('Aged Brie', 2, 0),
            new Item('Elixir of the Mongoose', 5, 7),
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
            new Item('Sulfuras, Hand of Ragnaros', -1, 80),
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
            // this conjured item does not work properly yet
            new Item('Conjured Mana Cake', 3, 6),
        ];

        $app = new GildedRose($items);

        for ($i = 0; $i < 31; $i++) {
            $received .= "-------- day ${i} --------" . PHP_EOL;
            $received .= 'name, sellIn, quality' . PHP_EOL;
            foreach ($items as $item) {
                $received .= $item . PHP_EOL;
            }
            $received .= PHP_EOL;
            $app->updateQuality();
        }

        Approvals::verifyString($received);
    }
}
