<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

interface CommandInterface
{
    public function exec(): void;
}
