<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

interface TypeInterface extends \Stringable
{
    /**
     * Type reference string representation.
     *
     * @return non-empty-string
     */
    public function __toString(): string;
}
