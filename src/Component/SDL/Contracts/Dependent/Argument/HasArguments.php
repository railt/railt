<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Contracts\Dependent\Argument;

use Railt\Component\SDL\Contracts\Dependent\ArgumentDefinition;

/**
 * The interface indicates that the type is a container that
 * contains a list of valid arguments in the type.
 */
interface HasArguments
{
    /**
     * @return iterable|ArgumentDefinition[]
     */
    public function getArguments(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool;

    /**
     * @param string $name
     * @return null|ArgumentDefinition
     */
    public function getArgument(string $name): ?ArgumentDefinition;

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
