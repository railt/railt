<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Resolve;

use Railt\Contracts\Http\InputInterface;

abstract class ResolveEvent
{
    public function __construct(
        public readonly InputInterface $input,
    ) {}

    public function getInput(): InputInterface
    {
        return $this->input;
    }
}
