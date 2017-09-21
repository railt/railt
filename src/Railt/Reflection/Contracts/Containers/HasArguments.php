<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Containers;

use Railt\Reflection\Contracts\Types\ArgumentType;

/**
 * Interface HasArguments
 */
interface HasArguments
{
    /**
     * @return iterable|ArgumentType[]
     */
    public function getArguments(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool;

    /**
     * @param string $name
     * @return null|ArgumentType
     */
    public function getArgument(string $name): ?ArgumentType;

    /**
     * @return int
     */
    public function getNumberOfArguments(): int;

    /**
     * @return int
     */
    public function getNumberOfRequiredArguments(): int;

    /**
     * @return int
     */
    public function getNumberOfOptionalArguments(): int;
}
