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
use Railt\Reflection\Builder\AbstractBuilder;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Support\NameBuilder;
use Railt\Reflection\Contracts\Types\Directive\Argument;
use Railt\Reflection\Contracts\Types\Directive\DirectiveInvocation;
use Railt\Reflection\Contracts\Types\DirectiveType;
use Railt\Reflection\Exceptions\BuildingException;

/**
 * Class DirectiveInvocationBuilder
 */
class DirectiveInvocationBuilder extends AbstractBuilder implements DirectiveInvocation
{
    use NameBuilder;

    /**
     * DirectiveInvocationBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws BuildingException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        parent::__construct($ast, $document);

        $this->bootNameBuilder($ast);
    }

    public function getDirective(): DirectiveType
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getArguments(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function hasArgument(string $name): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getArgument(string $name): ?Argument
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getNumberOfArguments(): int
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
