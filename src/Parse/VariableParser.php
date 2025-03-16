<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeDumper;
use PhpParser\NodeVisitor\FindingVisitor;
use PhpParser\Parser;
use Smeghead\PhpVariableHardUsage\Parse\Exception\ParseFailedException;

final class VariableParser
{
    private Parser $parser;

    public function __construct()
    {
        $this->parser = (new \PhpParser\ParserFactory())->createForNewestSupportedVersion();
    }

    /**
     * @param list<Stmt> $stmt
     * @return list<Function_>
     */
    private function getFunctions(array $stmt): array
    {
        $functionVisitor = new FindingVisitor(function ($node) {
            return $node instanceof Function_;
        });
        $traverser = new \PhpParser\NodeTraverser();
        $traverser->addVisitor($functionVisitor);
        $traverser->traverse($stmt);

        return $functionVisitor->getFoundNodes(); // @phpstan-ignore-line
    }

    /**
     * @param Function_|ClassMethod $function
     * @return list<Variable>
     */
    private function getVariables(Function_|ClassMethod $function): array
    {
        $variableVisitor = new FindingVisitor(function ($node) {
            return $node instanceof Variable;
        });
        $traverser = new \PhpParser\NodeTraverser();
        $traverser->addVisitor($variableVisitor);
        $traverser->traverse([$function]);

        return $variableVisitor->getFoundNodes(); // @phpstan-ignore-line
    }

    /**
     * @param list<Stmt> $stmts
     * @return list<Func>
     */
    private function parseFunctions(array $stmts): array
    {
        $foundFunctions = $this->getFunctions($stmts);

        $functions = [];
        foreach ($foundFunctions as $foundFunction) {
            $variables = $this->getVariables($foundFunction);
            $func = new Func($foundFunction->name->name);
            foreach ($variables as $variable) {
                $func->addVariable(new VarReference($variable->name, $variable->getLine())); // @phpstan-ignore-line
            }
            $functions[] = $func;
        }
        return $functions;
    }

    /**
     * @param list<Stmt> $stmt
     * @return list<Class_>
     */
    private function getClasses(array $stmt): array
    {
        $classVisitor = new FindingVisitor(function ($node) {
            return $node instanceof \PhpParser\Node\Stmt\Class_;
        });
        $traverser = new \PhpParser\NodeTraverser();
        $traverser->addVisitor($classVisitor);
        $traverser->traverse($stmt);

        return $classVisitor->getFoundNodes(); // @phpstan-ignore-line
    }

    /**
     * @param list<Stmt> $stmts
     * @return list<Func>
     */
    private function parseClasses(array $stmts): array
    {
        $foundClasses = $this->getClasses($stmts);

        $methods = [];
        foreach ($foundClasses as $foundClass) {
            foreach ($foundClass->getMethods() as $method) {
                $variables = $this->getVariables($method);
                $func = new Func(sprintf('%s::%s', $foundClass->name, $method->name->name));
                foreach ($variables as $variable) {
                    $func->addVariable(new VarReference($variable->name, $variable->getLine())); // @phpstan-ignore-line
                }
                $methods[] = $func;
            }
        }
        return $methods;
    }

    public function parse(string $content): ParseResult
    {
        $stmts = $this->parser->parse($content);
        if ($stmts === null) {
            throw new ParseFailedException();
        }

        $functions = $this->parseFunctions($stmts) + $this->parseClasses($stmts);

        return new ParseResult($functions);
    }
}