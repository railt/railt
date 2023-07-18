<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Exception\RuntimeExceptionInterface;

interface CommandInterface
{
    /**
     * @throws RuntimeExceptionInterface
     */
    public function exec(): void;
}
