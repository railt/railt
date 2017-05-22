<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Contracts;

use Serafim\Railgun\Contracts\Types\InternalTypeInterface;
use Serafim\Railgun\Contracts\Types\TypeInterface;

/**
 * Interface TypesRegistryInterface
 * @package Serafim\Railgun\Contracts
 */
interface TypesRegistryInterface
{
    /**
     * @param string|SchemaInterface $class
     * @return SchemaInterface
     */
    public function schema(string $class): SchemaInterface;

    /**
     * @param TypeInterface $type Type class implementation
     * @param string[] ...$aliases type aliases
     * @return TypesRegistryInterface
     */
    public function add(TypeInterface $type, string ...$aliases): TypesRegistryInterface;

    /**
     * @param string $name Type class name or alias
     * @param bool $withInternal
     * @return TypeInterface Type class implementation
     */
    public function get(string $name, bool $withInternal = true): TypeInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function isInternal(string $name): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function isAlias(string $name): bool;

    /**
     * @param string $name Type name or alias
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $original Original type name
     * @param string[] ...$aliases
     * @return TypesRegistryInterface
     */
    public function alias(string $original, string ...$aliases): TypesRegistryInterface;

    /**
     * @return iterable|TypeInterface[]
     */
    public function all(): iterable;
}
