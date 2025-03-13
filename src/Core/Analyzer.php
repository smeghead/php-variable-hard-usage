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

final class Analyzer
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

    public function analyze(string $content): AnalysisResult
    {
        $stmts = $this->parser->parse($content);
        if ($stmts === null) {
            throw new ParseFailedException();
        }

        $nodeDumper = new NodeDumper();

        $foundFunctions = $this->getFunctions($stmts);

        foreach ($foundFunctions as $foundFunction) {
            echo $nodeDumper->dump($foundFunction), "\n";
            $variables = $this->getVariables($foundFunction);
            echo "Found function: ", $foundFunction->name->name, "\n";
            foreach ($variables as $variable) {
                echo "Found variable: ", $variable->name, "\n";
            }
        }
        return new AnalysisResult(0, 0, 0, []);
    }
}