<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Dependent\Argument;

use Railt\Reflection\Contracts\Invocations\ArgumentInvocation;

/**
 * Interface HasPassedArguments
 */
interface HasPassedArguments
{
    /**
     * @return iterable|ArgumentInvocation[]
     */
    public function getPassedArguments(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasPassedArgument(string $name): bool;

    /**
     * @param string $name
     * @return null|ArgumentInvocation
     */
    public function getPassedArgument(string $name): ?ArgumentInvocation;

    /**
     * @return int
     */
    public function getNumberOfPassedArguments(): int;
}
