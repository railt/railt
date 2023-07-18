<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

/**
 * @template T of TypeInterface
 *
 * @template-implements WrappingTypeInterface<T>
 */
abstract class WrappingType implements WrappingTypeInterface
{


    /**
     * @return T
     */
    public function getOfType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return non-empty-string
     */
    abstract public function __toString(): string;
}
