<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Resolve;

use Railt\Contracts\Http\InputInterface;
use Railt\TypeSystem\Definition\FieldDefinition;

final class FieldResolved extends ResolveEvent
{
    /**
     * @param InputInterface<FieldDefinition> $input
     */
    public function __construct(
        InputInterface $input,
        mixed $parent,
        public readonly mixed $result,
    ) {
        parent::__construct($input, $parent);
    }
}
