<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

interface BuildCommandInterface extends CommandInterface
{
    public function exec(): void;
}
