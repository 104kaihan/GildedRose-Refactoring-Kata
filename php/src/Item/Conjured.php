<?php

declare(strict_types=1);

namespace GildedRose\Item;

use GildedRose\Item;

class Conjured
{
    use ItemCore;

    /**
     * Conjured: Quality 處理
     * `Quality`下降速度比正常物品快一倍
     *
     * @param Item $item
     */
    public function updateQuality(Item $item): void
    {
        $this->safeDecreaseQuality($item);
        $this->safeDecreaseQuality($item);

        // 品质`Quality`会以双倍速度加速下降
        if ($this->isExpired($item)) {
            $this->safeDecreaseQuality($item);
            $this->safeDecreaseQuality($item);
        }
    }
}

