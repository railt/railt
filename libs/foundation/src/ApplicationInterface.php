<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Foundation\Extension\ExtensionInterface;

interface ApplicationInterface extends ConnectorInterface
{
    public function extend(ExtensionInterface $extension): void;
}
