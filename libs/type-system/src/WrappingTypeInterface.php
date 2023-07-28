<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

/**
 * These types wrap and modify other types.
 *
 * @template T of TypeInterface
 */
interface WrappingTypeInterface extends TypeInterface
{
    /**
     * @param class-string<TypeInterface> $class
     */
    public function is(string $class): bool;

    /**
     * @return T
     */
    public function getOfType(): TypeInterface;
}
