<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Reflection\Abstraction\ArgumentInterface;
use Serafim\Railgun\Reflection\Abstraction\Common\HasArgumentsInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Serafim\Railgun\Reflection\Argument;

/**
 * Trait HasArguments
 * @package Serafim\Railgun\Reflection\Common
 * @mixin HasArgumentsInterface
 */
trait Arguments
{
    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @return iterable|ArgumentInterface[]
     */
    public function getArguments(): iterable
    {
        return array_values($this->arguments);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        return array_key_exists($name, $this->arguments);
    }

    /**
     * @param string $name
     * @return null|ArgumentInterface
     */
    public function getArgument(string $name): ?ArgumentInterface
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @param DocumentTypeInterface $document
     * @param TreeNode $ast
     * @throws \LogicException
     */
    protected function compileArguments(DocumentTypeInterface $document, TreeNode $ast): void
    {
        $allowed = in_array($ast->getId(), (array)($this->astHasArguments ?? ['#Argument']), true);

        if ($allowed && $this instanceof NamedDefinitionInterface) {
            $argument = new Argument($document, $ast, $this);
            $this->arguments[$argument->getName()] = $argument;
        }
    }
}
