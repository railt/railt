<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Schema;

use Railt\SDL\DictionaryInterface;

final class SchemaCompiled extends SchemaEvent
{
    public function __construct(
        public readonly DictionaryInterface $types,
    ) {}
}
