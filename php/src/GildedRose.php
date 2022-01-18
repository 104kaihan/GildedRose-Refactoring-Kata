<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\Item\Aged;
use GildedRose\Item\Backstage;

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
                $this->conjuredUpdateQuality($item);
            } else { // 其他 item
                $this->otherUpdateQuality($item);
            }
        }
    }

    /**
     * Quality 会随着时间推移而提高 但`Quality`永远不会超过50
     *
     * @param $item
     */
    private function safeIncreaseQuality($item): void
    {
        if ($item->quality < 50) {
            $item->quality += 1;
        }
    }

    /**
     * Quality 会随着时间推移而下降 但`Quality`永远不会为负值
     *
     * @param $item
     */
    private function safeDecreaseQuality($item): void
    {
        if ($item->quality > 0) {
            $item->quality -= 1;
        }
    }

    /**
     * Conjured item : Quality 處理
     *
     * `Quality`下降速度比正常物品快一倍
     *
     * @param $item
     */
    private function conjuredUpdateQuality($item): void
    {
        $this->otherUpdateQuality($item);
        $this->otherUpdateQuality($item);
    }

    /**
     * 其他一般 item : Quality 處理
     *
     * @param $item
     */
    private function otherUpdateQuality($item): void
    {
        $this->safeDecreaseQuality($item);

        // 品质`Quality`会以双倍速度加速下降
        if ($this->isExpired($item)) {
            $this->safeDecreaseQuality($item);
        }
    }

    /**
     * 销售期限过期
     *
     * @param $item
     *
     * @return bool true 過期
     */
    private function isExpired($item): bool
    {
        return $item->sell_in < 0;
    }
}
