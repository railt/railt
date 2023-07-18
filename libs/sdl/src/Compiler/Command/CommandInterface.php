<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Exception\RuntimeExceptionInterface;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler
 */
interface CommandInterface
{
    /**
     * @throws RuntimeExceptionInterface
     */
    public function exec(): void;
}
