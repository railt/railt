<?php

declare(strict_types=1);

namespace Railt\SDL;

use Railt\TypeSystem\DefinitionInterface;

final class StandardLibraryLoader
{
    /**
     * @var non-empty-string
     */
    private const DEFAULT_DIRECTORY = __DIR__ . '/../resources/stdlib/';

    /**
     * @psalm-taint-sink file $directory
     * @param non-empty-string $directory
     */
    public function __construct(
        private readonly string $directory = self::DEFAULT_DIRECTORY,
    ) {
    }

    /**
     * @param non-empty-string $name
     * @return non-empty-string
     */
    private function directivePathname(string $name): string
    {
        return $this->directory . '/@' . $name . '.graphql';
    }

    /**
     * @param non-empty-string $name
     * @return non-empty-string
     */
    private function typePathname(string $name): string
    {
        return $this->directory . '/' . $name . '.graphql';
    }

    /**
     * @param non-empty-string $name
     */
    public function __invoke(string $name, DefinitionInterface $from = null): ?\SplFileInfo
    {
        $files = [
            $this->directivePathname($name),
            $this->typePathname($name),
        ];

        foreach ($files as $pathname) {
            if (\is_file($pathname) && \is_readable($pathname)) {
                return new \SplFileInfo($pathname);
            }
        }

        return null;
    }
}
