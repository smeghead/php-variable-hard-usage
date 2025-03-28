<?php

/** ローカル変数酷使度: 45 */
function createCsvLine3($values) {
    $columns = [];

    foreach ($values as $value) {
        // 文字列なら " で囲む
        if (is_string($value)) {
            $formattedValue = '"' . $value . '"';
        } else {
            $formattedValue = $value;
        }

        $columns[] = $formattedValue;
    }

    return implode(',', $columns);
}
