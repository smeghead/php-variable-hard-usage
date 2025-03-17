<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse\Visitor;

use PhpParser\Node;
use PhpParser\NodeVisitor\FindingVisitor;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * FindingVisitor that searches for elements of FunctionLike
 * 
 * For ClassMethod, set the class name to ClassName as Attribute.
 */
class FunctionLikeFindingVisitor extends FindingVisitor {
    protected array $foundNodes = [];
    private ?string $currentClass = null;

    public function enterNode(Node $node) {
        if ($node instanceof Class_) {
            // Record class name
            $this->currentClass = $node->name ? $node->name->name : null;
        }

        if ($node instanceof ClassMethod) {
            $node->setAttribute('className', $this->currentClass);
        }
        return parent::enterNode($node);
    }

    public function leaveNode(Node $node) {
        if ($node instanceof Class_) {
            // Reset because it leaves the class scope
            $this->currentClass = null;
        }
    }
}
