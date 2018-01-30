<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface Dictionary
 */
interface Dictionary extends \Traversable, \Countable
{
    /**
     * @param string $name
     * @param TypeDefinition|null $from
     * @return TypeDefinition
     */
    public function get(string $name, TypeDefinition $from = null): TypeDefinition;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param TypeDefinition $type
     * @return Dictionary
     */
    public function add(TypeDefinition $type): Dictionary;

    /**
     * @return iterable|TypeDefinition[]
     */
    public function all(): iterable;
}
