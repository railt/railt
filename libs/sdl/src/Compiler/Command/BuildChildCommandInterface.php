<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

interface BuildChildCommandInterface extends BuildCommandInterface
{
    public function exec(): void;
}
