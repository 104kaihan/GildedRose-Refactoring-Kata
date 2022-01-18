<?php

declare(strict_types=1);

namespace GildedRose;

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

            if (!$aged and !$backstage) {
                if ($item->quality > 0) {
                    if (!$sulfuras) {
                        $item->quality -= 1;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality += 1;
                    if ($backstage) {
                        if ($item->sell_in < 11) {
                            if ($item->quality < 50) {
                                $item->quality += 1;
                            }
                        }
                        if ($item->sell_in < 6) {
                            if ($item->quality < 50) {
                                $item->quality += 1;
                            }
                        }
                    }
                }
            }

            if (!$sulfuras) {
                $item->sell_in -= 1;
            }

            if ($item->sell_in < 0) {
                if (!$aged) {
                    if (!$backstage) {
                        if ($item->quality > 0) {
                            if (!$sulfuras) {
                                $item->quality -= 1;
                            }
                        }
                    } else {
                        $item->quality -= $item->quality;
                    }
                } else {
                    if ($item->quality < 50) {
                        $item->quality += 1;
                    }
                }
            }
        }
    }
}
