<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Resolve;

use Railt\Contracts\Http\InputInterface;
use Railt\TypeSystem\Definition\FieldDefinition;

abstract class ResolveEvent
{
    /**
     * @param InputInterface<FieldDefinition> $input
     */
    public function __construct(
        public readonly InputInterface $input,
        public readonly mixed $parent = null,
    ) {}
}
