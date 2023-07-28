<?php

declare(strict_types=1);

namespace Railt\Router\Event;

use Railt\Contracts\Http\InputInterface;

abstract class ParameterEvent
{
    public function __construct(
        public readonly InputInterface $input,
        public readonly \ReflectionParameter $parameter,
    ) {
    }
}
