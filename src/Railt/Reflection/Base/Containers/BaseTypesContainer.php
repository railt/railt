<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Containers;

use Railt\Reflection\Contracts\Containers\HasTypes;
use Railt\Reflection\Contracts\Types\TypeDefinition;

/**
 * Trait BaseTypesContainer
 * @mixin HasTypes
 */
trait BaseTypesContainer
{
    /**
     * @var array
     */
    protected $types = [];

    /**
     * @return iterable|TypeDefinition[]
     */
    public function getTypes(): iterable
    {
        return \array_values($this->resolve()->types);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool
    {
        return \array_key_exists($name, $this->resolve()->types);
    }

    /**
     * @param string $name
     * @return null|TypeDefinition
     */
    public function getType(string $name): ?TypeDefinition
    {
        return $this->resolve()->types[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfTypes(): int
    {
        return \count($this->resolve()->types);
    }
}
