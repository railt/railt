<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Exception;

use Railt\SDL\Exception\RuntimeExceptionInterface;

interface FormatterInterface
{
    public function format(RuntimeExceptionInterface $e): RuntimeExceptionInterface;
}
