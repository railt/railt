<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\TypeLoaderInterface;
use Railt\TypeSystem\DefinitionInterface;

/**
 * @phpstan-import-type SourceType from TypeLoaderInterface
 * @psalm-import-type SourceType from TypeLoaderInterface
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class TypeLoader implements TypeLoaderInterface
{
    /**
     * @var list<callable(non-empty-string, DefinitionInterface|null):(SourceType|null)>
     */
    private array $loaders = [];

    /**
     * @param iterable<callable(non-empty-string, DefinitionInterface|null):(SourceType|null)> $loaders
     */
    public function __construct(iterable $loaders = [])
    {
        foreach ($loaders as $loader) {
            $this->addLoader($loader);
        }
    }

    public function addLoader(callable $loader): void
    {
        $this->loaders[] = $loader;
    }

    public function removeLoader(callable $loader): void
    {
        foreach ($this->loaders as $id => $actual) {
            if ($actual === $loader) {
                unset($this->loaders[$id]);
            }
        }

        $this->loaders = \array_values($this->loaders);
    }

    public function getLoaders(): iterable
    {
        return $this->loaders;
    }

    /**
     * @param non-empty-string $name
     * @return SourceType|null
     */
    public function __invoke(string $name, DefinitionInterface $from = null): mixed
    {
        foreach ($this->loaders as $loader) {
            $result = $loader($name, $from);

            if ($result !== null) {
                return $result;
            }
        }

        return null;
    }
}
