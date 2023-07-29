<?php

declare(strict_types=1);

namespace Railt\Router\Event;

use Railt\Contracts\Http\InputInterface;
use Railt\Router\Route;

/**
 * @template TResult of mixed
 */
class ActionDispatched extends ActionEvent
{
    /**
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
