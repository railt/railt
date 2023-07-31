<?php

declare(strict_types=1);

namespace Railt\SDL;

use Railt\SDL\Config;
use Railt\TypeSystem\DefinitionInterface;

final class StandardLibraryLoader
{
    /**
     * @var non-empty-string
     */
    private const DEFAULT_DIRECTORY = __DIR__ . '/../resources/stdlib';

    /**
     * @var list<non-empty-string>
     */
    private array $directories = [];

    public function __construct(Config $config)
    {
        foreach ($config->spec->getDependencies() as $spec) {
            $this->directories[] = self::DEFAULT_DIRECTORY . '/' . $spec->value;
        }
    }

    /**
     * @param non-empty-string $name
     */
    public function __invoke(string $name, DefinitionInterface $from = null): ?\SplFileInfo
    {
        foreach ($this->directories as $directory) {
            $pathname = $directory . '/' . $name . '.graphql';

            if (\is_file($pathname) && \is_readable($pathname)) {
                return new \SplFileInfo($pathname);
            }
        }

        return null;
    }
}
