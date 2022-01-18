<?php

declare(strict_types=1);

namespace GildedRose\Item;

use GildedRose\Item;

class Aged
{
    use ItemCore;

    /**
     * Aged Brie: Quality 處理
     * 
     * @param Item $item
     */
    public function updateQuality(Item $item): void
    {
        $this->safeIncreaseQuality($item);

        if ($this->isExpired($item)) {
            $this->safeIncreaseQuality($item);
        }
    }
}

