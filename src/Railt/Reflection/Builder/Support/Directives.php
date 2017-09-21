<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Support;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Builder\Directive\DirectiveInvocationBuilder;
use Railt\Reflection\Contracts\Containers\HasDirectives;
use Railt\Reflection\Contracts\Types\Directive\DirectiveInvocation;

/**
 * Trait Directives
 * @mixin HasDirectives
 */
trait Directives
{
    /**
     * @var array|DirectiveInvocation[]
     */
    private $directives = [];

    /**
     * @param TreeNode $ast
     * @return bool
     */
    protected function compileDirectives(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Directive') {
            $directive = new DirectiveInvocationBuilder($ast, $this->getDocument());
            $this->directives[$directive->getName()] = $directive;
            return true;
        }

        return false;
    }

    /**
     * @return iterable|DirectiveInvocation[]
     */
    public function getDirectives(): iterable
    {
        return \array_values($this->compiled()->directives);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool
    {
        return \array_key_exists($name, $this->compiled()->directives);
    }

    /**
     * @param string $name
     * @return null|DirectiveInvocation
     */
    public function getDirective(string $name): ?DirectiveInvocation
    {
        return $this->compiled()->directives[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfDirectives(): int
    {
        return \count($this->compiled()->directives);
    }
}
