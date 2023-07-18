<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Compiler\Context;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
abstract class Command implements CommandInterface
{
    public function __construct(
        protected readonly Context $ctx,
    ) {}
}
