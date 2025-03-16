<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use Smeghead\PhpVariableHardUsage\Parse\Exception\ParseFailedException;
final class VariableParser
{
    private Parser $parser;

    public function __construct()
    {
        $this->parser = (new \PhpParser\ParserFactory())->createForNewestSupportedVersion();
    }

    public function parse(string $content): ParseResult
    {
        $stmts = $this->parser->parse($content);
        if ($stmts === null) {
            throw new ParseFailedException();
        }

        $nodeFinder = new NodeFinder();

        $functionLikes = $nodeFinder->findInstanceOf($stmts, FunctionLike::class);

        $functions = array_map(function (FunctionLike $function) use ($nodeFinder) {
            $func = new Func($function->name->name);
            $variables = $nodeFinder->findInstanceOf($function, Variable::class);
            foreach ($variables as $variable) {
                $func->addVariable(new VarReference($variable->name, $variable->getLine()));
            }
            return $func;
        }, $functionLikes);

        return new ParseResult($functions);
    }
}