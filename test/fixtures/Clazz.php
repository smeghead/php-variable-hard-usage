<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Fixtures;

class Clazz
{
    public function bigFunction(): void
    {
        $num = 1; // 5行目

        if ($num === 1) {
            $num = 2; // 9行目
        }

        echo $num; // 12行目
    }
}