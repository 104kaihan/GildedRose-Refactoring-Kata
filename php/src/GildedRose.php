<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\Item\Aged;
use GildedRose\Item\Backstage;
use GildedRose\Item\Conjured;
use GildedRose\Item\Other;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $aged = $item->name === 'Aged Brie';
            $backstage = $item->name === 'Backstage passes to a TAFKAL80ETC concert';
            $sulfuras = $item->name === 'Sulfuras, Hand of Ragnaros';
            $conjured = $item->name === 'Conjured Mana Cake';

            // "Sulfuras"（萨弗拉斯—炎魔拉格纳罗斯之手）永不过期，也不会降低品质`Quality`
            if ($sulfuras) {
                continue;
            }

            $item->sell_in -= 1;

            // "Backstage passes"（后台通行证）与"Aged Brie"（陈年布利奶酪）类似，其品质`Quality`会随着时间推移而提高
            if ($aged) {
                (new Aged())->updateQuality($item);
            } elseif ($backstage) {
                (new Backstage())->updateQuality($item);
            } elseif ($conjured) {
                (new Conjured())->updateQuality($item);
            } else { // 其他 item
                (new Other())->updateQuality($item);
            }
        }
    }
}
