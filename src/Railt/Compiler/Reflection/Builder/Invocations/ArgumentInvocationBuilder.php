<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Invocations;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Base\Invocations\BaseArgumentInvocation;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Builder\Process\ValueBuilder;
use Railt\Compiler\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Compiler\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Compiler\Exceptions\TypeNotFoundException;

/**
 * Class ArgumentInvocationBuilder
 */
class ArgumentInvocationBuilder extends BaseArgumentInvocation implements Compilable
{
    use Compiler;

    /**
     * ArgumentBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param DirectiveInvocation $parent
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, DirectiveInvocation $parent)
    {
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
            $this->value = ValueBuilder::parse($ast->getChild(0));
        }

        return false;
    }

    /**
     * @return ArgumentDefinition
     */
    public function getDefinition(): ArgumentDefinition
    {
        return $this->parent->getDefinition()->getArgument($this->getName());
    }
}
