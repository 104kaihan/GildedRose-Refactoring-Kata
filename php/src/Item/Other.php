<?php

declare(strict_types=1);

namespace GildedRose\Item;

use GildedRose\Item;

class Other
{
    use ItemCore;

    /**
     * 其他一般 item : Quality 處理
     *
     * @param Item $item
     */
    public function updateQuality(Item $item): void
    {
        $this->safeDecreaseQuality($item);

        // 品质`Quality`会以双倍速度加速下降
        if ($this->isExpired($item)) {
            $this->safeDecreaseQuality($item);
        }
    }
}

