<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Core;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeDumper;
use PhpParser\NodeVisitor\FindingVisitor;
use PhpParser\Parser;
use Smeghead\PhpVariableHardUsage\Core\Exception\ParseFailedException;

final class VariableParser
{
    private Parser $parser;

    public function __construct()
    {
        $this->parser = (new \PhpParser\ParserFactory())->createForNewestSupportedVersion();
    }

    /**
     * @param Stmt[] $stmt
     * @return Function_[]
     */
    private function getFunctions(array $stmt): array
    {
        $functionVisitor = new FindingVisitor(function ($node) {
            return $node instanceof Function_;
        });
        $traverser = new \PhpParser\NodeTraverser();
        $traverser->addVisitor($functionVisitor);
        $traverser->traverse($stmt);

        return $functionVisitor->getFoundNodes();
    }

    private function getVariables(Function_ $function): array
    {
        $variableVisitor = new FindingVisitor(function ($node) {
            return $node instanceof Variable;
        });
        $traverser = new \PhpParser\NodeTraverser();
        $traverser->addVisitor($variableVisitor);
        $traverser->traverse([$function]);

        return $variableVisitor->getFoundNodes();
    }

    public function parse(string $content): ParseResult
    {
        $stmts = $this->parser->parse($content);
        if ($stmts === null) {
            throw new ParseFailedException();
        }

        $foundFunctions = $this->getFunctions($stmts);

        $functions = [];
        foreach ($foundFunctions as $foundFunction) {
            $variables = $this->getVariables($foundFunction);
            $func = new Func($foundFunction->name->name);
            foreach ($variables as $variable) {
                $func->addVariable(new VarReference($variable->name, $variable->getLine()));
            }
            $functions[] = $func;
        }
        return new ParseResult($functions);
    }
}