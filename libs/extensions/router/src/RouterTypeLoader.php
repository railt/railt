<?php

declare(strict_types=1);

namespace Railt\Extension\Router;

final class RouterTypeLoader
{
    /**
     * @var non-empty-string
     */
    public const PATHNAME = __DIR__ . '/../resources/route.graphql';

    /**
     * @var non-empty-string
     */
    public const DIRECTIVE_NAME = 'route';

    /**
     * @var non-empty-string
     */
    public const TYPE_NAME = '@' . self::DIRECTIVE_NAME;

    public function __invoke(string $type): ?\SplFileInfo
    {
        if ($type === self::TYPE_NAME) {
            return new \SplFileInfo(self::PATHNAME);
        }

        return null;
    }
}
