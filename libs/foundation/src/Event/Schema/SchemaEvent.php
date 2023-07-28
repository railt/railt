<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Schema;

use Railt\SDL\CompilerInterface;

abstract class SchemaEvent
{
    public function __construct(
        public readonly CompilerInterface $compiler,
    ) {}
}
