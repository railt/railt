<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Invocations\Directive;

use Railt\Compiler\Reflection\Base\Resolving;
use Railt\Compiler\Reflection\Contracts\Invocations\Directive\HasDirectives;
use Railt\Compiler\Reflection\Contracts\Invocations\DirectiveInvocation;

/**
 * Trait BaseDirectivesContainer
 *
 * @mixin HasDirectives
 * @mixin Resolving
 */
trait BaseDirectivesContainer
{
    /**
     * @var array|DirectiveInvocation[]
     */
    protected $directives = [];

    /**
     * @return iterable|DirectiveInvocation[]
     */
    public function getDirectives(): iterable
    {
        return \array_values($this->resolve()->directives);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool
    {
        return \array_key_exists($name, $this->resolve()->directives);
    }

    /**
     * @param string $name
     * @return null|DirectiveInvocation
     */
    public function getDirective(string $name): ?DirectiveInvocation
    {
        return $this->resolve()->directives[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfDirectives(): int
    {
        return \count($this->resolve()->directives);
    }
}
