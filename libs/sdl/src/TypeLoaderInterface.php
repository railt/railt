<?php

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Contracts\Source\ReadableInterface;
use Railt\TypeSystem\DefinitionInterface;

/**
 * @psalm-type SourceType = resource|string|\SplFileInfo|ReadableInterface
 * @phpstan-type SourceType resource|string|\SplFileInfo|ReadableInterface
 */
interface TypeLoaderInterface
{
    /**
     * @param callable(non-empty-string,DefinitionInterface|null=):(SourceType|null) $loader
     * @return void
     */
    public function addLoader(callable $loader): void;

    /**
     * @param callable(non-empty-string,DefinitionInterface|null=):(SourceType|null) $loader
     * @return void
     */
    public function removeLoader(callable $loader): void;

    /**
     * @return iterable<callable(non-empty-string,DefinitionInterface|null=):(SourceType|null)>
     */
    public function getLoaders(): iterable;
}
