<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Schema;

use Phplrt\Contracts\Source\ReadableInterface;
use Railt\SDL\CompilerInterface;
use Railt\SDL\DictionaryInterface;

final class SchemaCompiled extends SchemaEvent
{
    public function __construct(
        CompilerInterface $compiler,
        ReadableInterface $source,
        public readonly DictionaryInterface $types,
    ) {
        parent::__construct($compiler, $source);
    }
}
