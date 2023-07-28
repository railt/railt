<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Schema;

use Psr\EventDispatcher\StoppableEventInterface;
use Railt\Foundation\Event\PropagationStoppableEvent;

final class SchemaCompiling extends SchemaEvent implements StoppableEventInterface
{
    use PropagationStoppableEvent;
}
