<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse\Visitor;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\FunctionLike;
use PhpParser\NodeVisitor\FindingVisitor;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;

/**
 * FindingVisitor that searches for elements of FunctionLike
 * 
 * For ClassMethod, set the class name to ClassName as Attribute.
 */
class FunctionLikeFindingVisitor extends FindingVisitor {
    private ?string $currentClass = null;
    private ?string $currentNamespace = null;

    public function enterNode(Node $node) {
        if ($node instanceof Class_) {
            // Record class name
            $this->currentClass = $node->name ? $node->name->name : null;
        }
        if ($node instanceof Namespace_) {
            // Record class name
            $this->currentNamespace = $node->name ? $node->name->name : null;
        }

        if ($node instanceof Assign || $node instanceof AssignOp) {
            $node->var->setAttribute('assigned', true); // Mark as assigned
        }
        if ($node instanceof FunctionLike) {
            $node->setAttribute('namespace', $this->currentNamespace);
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
