<?php

/** ローカル変数酷使度: 131 */
function createCsvLine1($values) {
    $result = "";
    $count = count($values);

    for ($i = 0; $i < $count; $i++) {
        $value = $values[$i];

        // 文字列なら " で囲む
        if (is_string($value)) {
            $formattedValue = '"' . $value . '"';
        } else {
            $formattedValue = $value;
        }

        if ($i == 0) {
            $result .= $formattedValue;
        } else {
            $result .= "," . $formattedValue;
        }
    }

    return $result;
}