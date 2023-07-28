<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Schema;

use Railt\SDL\CompilerInterface;
use Railt\TypeSystem\DictionaryInterface;

final class SchemaCompiled extends SchemaEvent
{
    /**
     * @param resource|string|\SplFileInfo $source
     */
    public function __construct(
        CompilerInterface $compiler,
        mixed $source,
        public readonly DictionaryInterface $types,
    ) {
        parent::__construct($compiler, $source);
    }
}
