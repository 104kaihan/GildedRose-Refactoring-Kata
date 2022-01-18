<?php

declare(strict_types=1);

namespace GildedRose\Item;

use GildedRose\Item;

/**
 * 共用邏輯的放這裡
 */
trait ItemCore
{
    /**
     * Quality 会随着时间推移而提高 但`Quality`永远不会超过50
     *
     * @param Item $item
     */
    private function safeIncreaseQuality(Item $item): void
    {
        if ($item->quality < 50) {
            $item->quality += 1;
        }
    }

    /**
     * Quality 会随着时间推移而下降 但`Quality`永远不会为负值
     *
     * @param Item $item
     */
    private function safeDecreaseQuality(Item $item): void
    {
        if ($item->quality > 0) {
            $item->quality -= 1;
        }
    }

    /**
     * 销售期限过期
     *
     * @param Item $item
     *
     * @return bool true 過期
     */
    private function isExpired(Item $item): bool
    {
        return $item->sell_in < 0;
    }
}
