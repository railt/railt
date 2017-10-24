<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Contracts\Invocations\Directive;

use Railt\Compiler\Reflection\Contracts\Invocations\DirectiveInvocation;

/**
 * The interface indicates that the type is a container that
 * contains a list of directives in the type.
 */
interface HasDirectives
{
    /**
     * @return iterable|DirectiveInvocation[]
     */
    public function getDirectives(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool;

    /**
     * @param string $name
     * @return null|DirectiveInvocation
     */
    public function getDirective(string $name): ?DirectiveInvocation;

    /**
     * @return int
     */
    public function getNumberOfDirectives(): int;
}
