<?php

declare(strict_types=1);

namespace Railt\Router\Event;

use Railt\Contracts\Http\InputInterface;

final class ParameterResolved extends ParameterEvent
{
    public function __construct(
        InputInterface $input,
        \ReflectionParameter $parameter,
        public readonly mixed $result = null,
    ) {
        parent::__construct($input, $parameter);
    }
}
