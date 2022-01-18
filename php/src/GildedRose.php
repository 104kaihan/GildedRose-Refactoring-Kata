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

            $item->sell_in -= 1;

            // "Backstage passes"（后台通行证）与"Aged Brie"（陈年布利奶酪）类似，其品质`Quality`会随着时间推移而提高
            if ($aged) {
                $this->safeIncreaseQuality($item);
            } elseif ($backstage) {
                $this->safeIncreaseQuality($item);
                // 剩10天或更少的时候，品质`Quality`每天提高2
                if ($item->sell_in < 10) {
                    $this->safeIncreaseQuality($item);
                }
                // 5天或更少的时候，品质`Quality`每天提高3
                if ($item->sell_in < 5) {
                    $this->safeIncreaseQuality($item);
                }
            } else { // 其他 item
                $this->safeDecreaseQuality($item);
            }

            // 销售期限过期
            $expired = $item->sell_in < 0;
            if ($aged) {
                if ($expired) {
                    $this->safeIncreaseQuality($item);
                }
            } elseif ($backstage) {
                if ($expired) {
                    // 一旦过期，品质就会降为0
                    $item->quality -= $item->quality;
                }
            } else { // 其他 item，品质`Quality`会以双倍速度加速下降
                if ($expired) {
                    $this->safeDecreaseQuality($item);
                }
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
}
