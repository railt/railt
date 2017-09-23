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
use Railt\Reflection\Builder\AbstractNamedTypeBuilder;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Runtime\NamedTypeBuilder;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\Directive\Argument;
use Railt\Reflection\Contracts\Types\Directive\DirectiveInvocation;
use Railt\Reflection\Exceptions\TypeNotFoundException;

/**
 * Class ArgumentBuilder
 */
class ArgumentBuilder implements Argument
{
    use NamedTypeBuilder;

    /**
     *
     */
    private const AST_ID_VALUE = '#Value';

    /**
     * @var DirectiveInvocation
     */
    private $parent;

    /**
     * @var mixed
     */
    private $value;

    /**
     * ArgumentBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param Nameable|DirectiveInvocation $parent
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, Nameable $parent)
    {
        \assert($parent instanceof DirectiveInvocation);

        $this->parent = $parent;
        $this->bootNamedTypeBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === self::AST_ID_VALUE) {
            $this->value = $ast->getChild(0)->getValueValue();
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->compiled()->value;
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

    /**
     * @return Nameable
     */
    public function getParent(): Nameable
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Argument';
    }
}
