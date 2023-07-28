<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Phplrt\Contracts\Source\ReadableInterface;
use Railt\Contracts\Http\ConnectionInterface;
use Railt\Foundation\Extension\ExtensionInterface;

interface ApplicationInterface
{
    /**
     * @param string|resource|\SplFileInfo|ReadableInterface $schema
     * @return ConnectionInterface
     */
    public function connect(mixed $schema): ConnectionInterface;

    public function extend(ExtensionInterface $extension): void;
}
