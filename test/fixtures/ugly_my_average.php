<?php

/**
 * 最小値と最大値を除いて平均を返却します。
 */
function ugly_my_average(array $items): float {
    $minValue = PHP_INT_MAX;
    $maxValue = PHP_INT_MIN;
    
    for ($i = 0; $i < count($items); $i++) {
        $v = $items[$i];
        if ($v < $minValue) {
            $minValue = $v;
        }
        if ($v > $maxValue) {
            $maxValue = $v;
        }
    }
    $sum = 0;
    for ($i = 0; $i < count($items); $i++) {
        $v = $items[$i];
        if (in_array($v, [$minValue, $maxValue])) {
            continue;
        }
        $sum += $v;
    }
    return $sum / (count($items) - 2);
}

$items = [3, 99, 40, 45, 50, 52];
print_r(ugly_my_average($items) . PHP_EOL);
