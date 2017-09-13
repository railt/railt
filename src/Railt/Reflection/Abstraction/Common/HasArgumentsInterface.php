<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Abstraction\Common;

use Railt\Reflection\Abstraction\ArgumentInterface;

/**
 * Interface HasArgumentsInterface
 * @package Railt\Reflection\Abstraction\Common
 */
interface HasArgumentsInterface
{
    /**
     * @return iterable|ArgumentInterface[]
     */
    public function getArguments(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool;

    /**
     * @param string $name
     * @return null|ArgumentInterface
     */
    public function getArgument(string $name): ?ArgumentInterface;
}
