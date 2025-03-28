<?php

/** ローカル変数酷使度: 106 */
function createCsvLine2($values) {
    $result = "";

    foreach ($values as $value) {
        // 文字列なら " で囲む
        if (is_string($value)) {
            $formattedValue = '"' . $value . '"';
        } else {
            $formattedValue = $value;
        }

        if (empty($result)) {
            $result .= $formattedValue;
        } else {
            $result .= "," . $formattedValue;
        }
    }

    return $result;
}