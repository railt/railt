<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Contracts\Http\Request;

/**
 * Interface VariablesInterface
 */
interface VariablesInterface extends \Countable
{
    /**
     * @return array|mixed[]
     */
    public function getVariables(): array;

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getVariable(string $name, $default = null);

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable(string $name): bool;
}
