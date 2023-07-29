<?php

declare(strict_types=1);

namespace Railt\Router\Event;

/**
 * @template TResult of \Throwable
 *
 * @template-extends ActionDispatched<TResult>
 */
final class ActionFailed extends ActionDispatched
{
}
