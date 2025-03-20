<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

interface CommandInterface
{
    /**
     * コマンドを実行する
     * 
     * @return int 終了コード（成功時は0、失敗時は1以上）
     */
    public function execute(): int;
}