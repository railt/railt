<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types\Directive;

use Railt\Reflection\Contracts\Behavior\Invokable;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Types\DirectiveType;

/**
 * Interface DirectiveInvocation
 */
interface DirectiveInvocation extends Invokable, Nameable
{
    /**
     * @return DirectiveType
     */
    public function getDirective(): DirectiveType;

    /**
     * @return iterable|Argument[]
     */
    public function getArguments(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool;

    /**
     * @param string $name
     * @return null|Argument
     */
    public function getArgument(string $name): ?Argument;

    /**
     * @return int
     */
    public function getNumberOfArguments(): int;
}
