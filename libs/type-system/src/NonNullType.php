<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

/**
 * @template T of TypeInterface
 *
 * @template-extends WrappingType<T>
 */
final class NonNullType extends WrappingType implements InputTypeInterface, OutputTypeInterface
{
    public function __toString(): string
    {
        return \sprintf('%s!', (string)$this->type);
    }
}
