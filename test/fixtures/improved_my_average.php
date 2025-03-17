<?php

/**
 * 最小値と最大値を除いて平均を返却します。
 */
function improved_my_avearge(array $items): float {
    sort($items);
    $newItems = array_slice($items, 1, count($items) - 2);
    $sum = array_sum($newItems);
    return $sum / count($newItems);
}

$items = [3, 99, 40, 45, 50, 52];
print_r(improved_my_avearge($items) . PHP_EOL);
