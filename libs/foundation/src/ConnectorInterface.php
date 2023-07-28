<?php

declare(strict_types=1);

namespace Railt\Foundation;

interface ConnectorInterface
{
    /**
     * @param array<non-empty-string, mixed> $variables
     */
    public function connect(mixed $schema, array $variables = []): ConnectionInterface;
}
