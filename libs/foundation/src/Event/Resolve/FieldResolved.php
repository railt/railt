<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Resolve;

use Railt\Contracts\Http\InputInterface;

final class FieldResolved extends ResolveEvent
{
    public function __construct(
        InputInterface $input,
        public readonly mixed $result,
    ) {
        parent::__construct($input);
    }
}
