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
use Railt\Reflection\Contracts\Types\TypeInterface;

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
     * @return iterable|TypeInterface[]
     */
    public function getTypes(): iterable
    {
        return \array_values($this->compiled()->types);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool
    {
        return \array_key_exists($name, $this->compiled()->types);
    }

    /**
     * @param string $name
     * @return null|TypeInterface
     */
    public function getType(string $name): ?TypeInterface
    {
        return $this->compiled()->types[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfTypes(): int
    {
        return \count($this->compiled()->types);
    }
}
