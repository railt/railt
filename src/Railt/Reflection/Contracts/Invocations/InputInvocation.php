<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Invocations;

use Railt\Reflection\Contracts\Dependent\DependentDefinition;

/**
 * Interface InputInvocation
 */
interface InputInvocation extends DependentDefinition, Invocable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @return iterable|mixed[]
     */
    public function getPassedValues(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasPassedValue(string $name): bool;

    /**
     * @param string $name
     * @return mixed
     */
    public function getPassedValue(string $name);

    /**
     * @return int
     */
    public function getNumberOfPassedValues(): int;
}
