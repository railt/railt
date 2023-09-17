<?php

declare(strict_types=1);

namespace Railt\SDL\Generator;

final class Config
{
    /**
     * @param int<0, max> $indentationLevel
     */
    public function __construct(
        public readonly string $delimiter = "\n",
        public readonly string $indentation = '    ',
    ) {}
}
