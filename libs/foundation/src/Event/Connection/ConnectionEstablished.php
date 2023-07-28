<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Connection;

use Psr\EventDispatcher\StoppableEventInterface;
use Railt\Foundation\Event\PropagationStoppableEvent;

final class ConnectionEstablished extends ConnectionEvent implements StoppableEventInterface
{
    use PropagationStoppableEvent;
}
