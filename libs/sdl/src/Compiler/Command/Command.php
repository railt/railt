<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Compiler\Context;

abstract class Command implements CommandInterface
{
    public function __construct(
        protected readonly Context $ctx,
    ) {}
}
