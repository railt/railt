<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Schema;

use Phplrt\Contracts\Source\ReadableInterface;
use Railt\SDL\CompilerInterface;

abstract class SchemaEvent
{
    public function __construct(
        public readonly CompilerInterface $compiler,
        public readonly ReadableInterface $source,
    ) {}
}
