<?php

declare(strict_types=1);

namespace Railt\Extension\Router\Event;

use Railt\Contracts\Http\InputInterface;
use Railt\Extension\Router\Route;
use Railt\TypeSystem\Definition\FieldDefinition;

/**
 * @template TResult of mixed
 */
class ActionDispatched extends ActionEvent
{
    /**
     * @param InputInterface<FieldDefinition> $input
     * @param TResult $result
     */
    public function __construct(
        InputInterface $input,
        Route $route,
        public mixed $result,
    ) {
        parent::__construct($input, $route);
    }
}
