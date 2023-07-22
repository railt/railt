<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

/**
 * @template T of TypeInterface
 *
 * @template-implements WrappingTypeInterface<T>
 */
abstract class WrappingType extends Type implements WrappingTypeInterface
{
    /**
     * @param T $type
     */
    public function __construct(
        protected readonly TypeInterface $type,
    ) {
    }

    /**
     * @return T
     */
    public function getOfType(): TypeInterface
    {
        return $this->type;
    }
}
