<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Phplrt\Contracts\Source\ReadableInterface;
use Railt\Foundation\ConnectionInterface;

interface ApplicationInterface
{
    /**
     * @param string|resource|\SplFileInfo|ReadableInterface $schema
     * @return ConnectionInterface
     */
    public function connect(mixed $schema): ConnectionInterface;
}
