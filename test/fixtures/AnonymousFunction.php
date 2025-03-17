<?php

$items = ['ca', 'aa', 'bc', 'ab', 'ac'];
usort($items, function (string $a, string $b): int {
    return $a <=> $b;
});