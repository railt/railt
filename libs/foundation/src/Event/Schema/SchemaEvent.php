<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Schema;

use Railt\SDL\CompilerInterface;

abstract class SchemaEvent
{
    /**
     * @param resource|string|\SplFileInfo $source
     */
    public function __construct(
        public readonly CompilerInterface $compiler,
        public readonly mixed $source,
    ) {}
}
