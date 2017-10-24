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
use Railt\Compiler\Reflection\Base\Invocations\BaseDirectiveInvocation;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Contracts\Behavior\Nameable;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\DirectiveDefinition;

/**
 * Class DirectiveInvocationBuilder
 */
class DirectiveInvocationBuilder extends BaseDirectiveInvocation implements Compilable
{
    use Compiler;

    /**
     * DirectiveInvocationBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param Nameable $parent
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, Nameable $parent)
    {
        $this->parent = $parent;
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Argument') {
            $argument = new ArgumentInvocationBuilder($ast, $this->getDocument(), $this);

            $this->arguments[$argument->getName()] = $argument;

            return true;
        }

        return false;
    }

    /**
     * @return DirectiveDefinition|Definition
     */
    public function getDefinition(): DirectiveDefinition
    {
        $this->compileIfNotCompiled();

        return $this->getCompiler()->get($this->getName());
    }
}
