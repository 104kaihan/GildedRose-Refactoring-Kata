<?php

declare(strict_types=1);

namespace GildedRose\Item;

use GildedRose\Item;

class Backstage
{
    use ItemCore;

    /**
     * Backstage: Quality 處理
     *
     * @param Item $item
     */
    public function updateQuality(Item $item): void
    {
        $this->safeIncreaseQuality($item);

        // 剩10天或更少的时候，品质`Quality`每天提高2
        if ($item->sell_in < 10) {
            $this->safeIncreaseQuality($item);
        }
        // 5天或更少的时候，品质`Quality`每天提高3
        if ($item->sell_in < 5) {
            $this->safeIncreaseQuality($item);
        }

        if ($this->isExpired($item)) {
            // 一旦过期，品质就会降为0
            $item->quality -= $item->quality;
        }
    }
}

