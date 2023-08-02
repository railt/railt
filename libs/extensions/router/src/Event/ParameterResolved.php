<?php

declare(strict_types=1);

namespace Railt\Extension\Router\Event;

use Railt\Contracts\Http\InputInterface;
use Railt\TypeSystem\Definition\FieldDefinition;

final class ParameterResolved extends ParameterEvent
{
    /**
     * @param InputInterface<FieldDefinition> $input
     */
    public function __construct(
        InputInterface $input,
        \ReflectionParameter $parameter,
        public readonly array $value,
    ) {
        parent::__construct($input, $parameter);
    }
}
