<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Expression;

use Railt\SDL\Node\Node;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
abstract class Expression extends Node implements \Stringable
{
    /**
     * Returns human-readable string representation of the expression.
     */
    abstract public function __toString(): string;
}
