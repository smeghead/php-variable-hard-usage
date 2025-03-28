<?php

/** ローカル変数酷使度: 6 + 3 = 9 */
function createCsvLine5($values) {
    return implode(',', array_map(function ($value) {
        return is_string($value) ? '"' . $value . '"' : $value;
    }, $values));
}