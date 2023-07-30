<?php

declare(strict_types=1);

namespace Railt\Router\Event;

use Railt\Contracts\Http\InputInterface;
use Railt\Router\Route;
use Railt\TypeSystem\Definition\FieldDefinition;

abstract class ActionEvent
{
    /**
     * @param InputInterface<FieldDefinition> $input
     */
    public function __construct(
        public readonly InputInterface $input,
        public readonly Route $route,
    ) {
    }
}
