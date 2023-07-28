<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Resolve;

use Railt\Contracts\Http\InputInterface;

final class FieldResolved extends ResolveEvent
{
    public function __construct(
        InputInterface $input,
    ) {
        parent::__construct($input);
    }
}
