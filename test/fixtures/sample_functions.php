<?php

// 酷使する関数
function hardWordCount(string $text): int {
    $wordCount = 0;
    $currentWord = '';
    for ($i = 0; $i < strlen($text); $i++) {
        if ($text[$i] === ' ') {
            if ($currentWord !== '') {
                $wordCount++;
                $currentWord = '';
            }
        } else {
            $currentWord .= $text[$i];
        }
    }
    if ($currentWord !== '') {
        $wordCount++;
    }
    return $wordCount;
}

// あまり酷使しない関数
function lightWordCount(string $text): int {
    $words = explode(' ', $text);
    $wordCount = 0;
    foreach ($words as $word) {
        if ($word !== '') {
            $wordCount++;
        }
    }
    return $wordCount;
}

// 全然酷使しない関数
function niceWordCount(string $text): int {
    return count(array_filter(explode(' ', $text), fn($word) => $word !== ''));
}




$text = 'This is a pen.';

echo hardWordCount($text) . PHP_EOL;
echo lightWordCount($text) . PHP_EOL;
echo niceWordCount($text) . PHP_EOL;
