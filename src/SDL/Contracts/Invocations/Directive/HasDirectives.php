<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Contracts\Invocations\Directive;

use Railt\SDL\Contracts\Invocations\DirectiveInvocation;

/**
 * The interface indicates that the type is a container that
 * contains a list of directives in the type.
 */
interface HasDirectives
{
    /**
     * @param string|null $name
     * @return iterable|DirectiveInvocation[]
     */
    public function getDirectives(string $name = null): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool;

    /**
     * @return int
     */
    public function getNumberOfDirectives(): int;
}
