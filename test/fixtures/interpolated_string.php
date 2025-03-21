<?php

function test(string $name): string
{
    return ${"Hello, {$name}!"};
}