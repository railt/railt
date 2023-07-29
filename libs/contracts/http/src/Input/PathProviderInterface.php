<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Input;

interface PathProviderInterface
{
    public const DEFAULT_PATH_DELIMITER = '.';

    /**
     * @return non-empty-list<non-empty-string>
     */
    public function getPath(): array;

    /**
     * @param non-empty-string $delimiter
     * @return non-empty-string
     */
    public function getPathAsString(string $delimiter = self::DEFAULT_PATH_DELIMITER): string;
}
