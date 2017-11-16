<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface Dictionary
 * @package Railt\Compiler\Compiler
 */
interface Dictionary
{
    /**
     * @param TypeDefinition $type
     * @param bool $force
     * @return Dictionary
     */
    public function register(TypeDefinition $type, bool $force = false): Dictionary;

    /**
     * @param string $name
     * @return TypeDefinition
     */
    public function get(string $name): TypeDefinition;

    /**
     * @return iterable|TypeDefinition[]
     */
    public function all(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;
}
