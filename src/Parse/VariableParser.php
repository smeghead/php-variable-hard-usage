<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use Smeghead\PhpVariableHardUsage\Parse\Exception\ParseFailedException;
use Smeghead\PhpVariableHardUsage\Parse\Visitor\FunctionLikeFindingVisitor;

final class VariableParser
{
    private Parser $parser;

    private NodeFinder $nodeFinder;

    public function __construct()
    {
        $this->parser = (new \PhpParser\ParserFactory())->createForNewestSupportedVersion();
        $this->nodeFinder = new NodeFinder();
    }

    public function parse(string $content): ParseResult
    {
        $stmts = $this->parser->parse($content);
        if ($stmts === null) {
            throw new ParseFailedException();
        }

        $traverser = new NodeTraverser();
        $visitor = new FunctionLikeFindingVisitor(fn($node) => $node instanceof FunctionLike);
        $traverser->addVisitor($visitor);
        $traverser->traverse($stmts);
        $functionLikes = $visitor->getFoundNodes();

        $functions = $this->collectParseResultPerFunctionLike($functionLikes);

        return new ParseResult($functions);
    }

    /**
     * @param list<FunctionLike> $functionLikes
     * @return list<Func>
     */
    private function collectParseResultPerFunctionLike(array $functionLikes): array
    {
        return array_map(function (FunctionLike $function) {
            $className = $function->getAttribute('className'); // Get the class name set in FunctionLikeFindingVisitor
            $functionName = $function->name->name ?? $function->getType() . '@' . $function->getStartLine();
            $functionIdentifier = sprintf(
                '%s%s',
                isset($className) ? $className . '::' : '',
                $functionName
            );

            $func = new Func($functionIdentifier);

            $variables = $this->nodeFinder->findInstanceOf($function, Variable::class);
            foreach ($variables as $variable) {
                $func->addVariable(new VarReference($variable->name, $variable->getLine())); // @phpstan-ignore-line
            }
            return $func;
        }, $functionLikes);
    }
}