<?php

/** ローカル変数酷使度: 15 */
function createCsvLine4($values) {
    $columns = [];

    foreach ($values as $value) {
        $columns[] = is_string($value) ? '"' . $value . '"' : $value;;
    }

    return implode(',', $columns);
}