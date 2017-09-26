<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Directive;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\Directive\BaseArgument;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\Compilable;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\Directive\DirectiveInvocation;
use Railt\Reflection\Exceptions\TypeNotFoundException;

/**
 * Class ArgumentBuilder
 */
class ArgumentBuilder extends BaseArgument implements Compilable
{
    use Builder;

    /**
     * ArgumentBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param Nameable $parent
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, Nameable $parent)
    {
        \assert($parent instanceof DirectiveInvocation);

        $this->parent = $parent;
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Value') {
            $this->value = $ast->getChild(0)->getValueValue();
        }

        return false;
    }

    /**
     * @return ArgumentType
     * @throws TypeNotFoundException
     */
    public function getArgument(): ArgumentType
    {
        $argument = $this->parent->getDirective()->getArgument($this->getName());

        if ($argument === null) {
            $error = 'Argument %s was specified at the @%s calling, but it absent in the directive itself.';
            throw new TypeNotFoundException(\sprintf($error, $this->getName(), $this->getParent()->getName()));
        }

        return $argument;
    }
}
