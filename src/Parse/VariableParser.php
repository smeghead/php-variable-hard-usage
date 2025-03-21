<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\InterpolatedStringPart;
use PhpParser\Node\Scalar\InterpolatedString;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
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
        /** @var list<FunctionLike> */
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
            /** @var string|null */
            $namespace = $function->getAttribute('namespace'); // Get the namespace name set in FunctionLikeFindingVisitor
            $className = $function->getAttribute('className'); // Get the class name set in FunctionLikeFindingVisitor
            $functionName = $function->name->name ?? $function->getType() . '@' . $function->getStartLine();
            $functionIdentifier = sprintf(
                '%s%s',
                isset($className) ? $className . '::' : '',
                $functionName
            );
            $func = new Func($namespace, $functionIdentifier);

            $variables = $this->nodeFinder->findInstanceOf($function, Variable::class);
            foreach ($variables as $variable) {
                $assigned = $variable->getAttribute('assigned');
                $func->addVariable(new VarReference($this->getVariableName($variable), $variable->getLine(), $assigned === true));
            }
            return $func;
        }, $functionLikes);
    }

    private function getVariableName(Variable $variable): string
    {
        if ($variable->name instanceof InterpolatedString) {
            $parts = $variable->name->parts;
            return sprintf('${"%s"}', implode('', array_map(function($part){
                if ($part instanceof Variable) {
                    return sprintf('{$%s}', $part->name->name ?? $part->name);
                } else if ($part instanceof InterpolatedStringPart) {
                    return $part->value;
                } else {
                    return '';
                }
            }, $parts)));
        }
        return $variable->name->name ?? $variable->name;
    }
}