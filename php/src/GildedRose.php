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

            // "Sulfuras"（萨弗拉斯—炎魔拉格纳罗斯之手）永不过期，也不会降低品质`Quality`
            if ($sulfuras) {
                continue;
            }

            // "Backstage passes"（后台通行证）与"Aged Brie"（陈年布利奶酪）类似，其品质`Quality`会随着时间推移而提高
            if ($aged) {
                if ($item->quality < 50) {
                    $item->quality += 1;
                }
            } elseif ($backstage) {
                if ($item->quality < 50) {
                    $item->quality += 1;
                    // 剩10天或更少的时候，品质`Quality`每天提高2
                    if ($item->sell_in < 11) {
                        if ($item->quality < 50) {
                            $item->quality += 1;
                        }
                    }
                    // 5天或更少的时候，品质`Quality`每天提高3
                    if ($item->sell_in < 6) {
                        if ($item->quality < 50) {
                            $item->quality += 1;
                        }
                    }
                }
            } elseif ($item->quality > 0) { // 其他 item
                $item->quality -= 1;
            }

            $item->sell_in -= 1;

            // 销售期限过期，品质`Quality`会以双倍速度加速下降
            if ($item->sell_in < 0) {
                if ($aged) {
                    if ($item->quality < 50) {
                        $item->quality += 1;
                    }
                } elseif ($backstage) {
                    // 旦过期，品质就会降为0
                    $item->quality -= $item->quality;
                } elseif ($item->quality > 0) { // 其他 item
                    $item->quality -= 1;
                }
            }
        }
    }
}
