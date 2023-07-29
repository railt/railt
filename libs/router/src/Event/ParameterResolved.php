<?php

declare(strict_types=1);

namespace Railt\Router\Event;

use Railt\Contracts\Http\InputInterface;

final class ParameterResolved extends ParameterEvent
{
    /**
     * @param InputInterface<object> $input
     */
    public function __construct(
        InputInterface $input,
        \ReflectionParameter $parameter,
        public readonly array $value,
    ) {
        parent::__construct($input, $parameter);
    }
}
