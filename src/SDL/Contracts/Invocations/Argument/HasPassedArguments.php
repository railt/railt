<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Contracts\Invocations\Argument;

/**
 * Interface HasPassedArguments
 */
interface HasPassedArguments
{
    /**
     * @return iterable|mixed[]
     */
    public function getPassedArguments(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasPassedArgument(string $name): bool;

    /**
     * @param string $name
     * @return mixed
     */
    public function getPassedArgument(string $name);

    /**
     * @return int
     */
    public function getNumberOfPassedArguments(): int;
}
