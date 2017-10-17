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
use Railt\Reflection\Base\Directive\BaseInvocation;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\Compilable;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Types\DirectiveType;
use Railt\Reflection\Contracts\Types\TypeDefinition;
use Railt\Reflection\Exceptions\TypeNotFoundException;

/**
 * Class DirectiveInvocationBuilder
 */
class DirectiveInvocationBuilder extends BaseInvocation implements Compilable
{
    use Builder;

    /**
     * DirectiveInvocationBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param Nameable $parent
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, Nameable $parent)
    {
        $this->parent = $parent;
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Argument') {
            $argument = new ArgumentBuilder($ast, $this->getDocument(), $this);

            $this->arguments[$argument->getName()] = $argument;

            return true;
        }

        return false;
    }

    /**
     * @return DirectiveType|TypeDefinition
     * @throws \Railt\Reflection\Exceptions\TypeNotFoundException
     */
    public function getDefinition(): DirectiveType
    {
        $this->compileIfNotCompiled();

        return $this->getCompiler()->get($this->getName());
    }
}
