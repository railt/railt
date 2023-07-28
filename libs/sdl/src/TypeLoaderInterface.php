<?php

declare(strict_types=1);

namespace Railt\SDL;

use Railt\TypeSystem\DefinitionInterface;

/**
 * @psalm-type SourceType = resource|string|\SplFileInfo
 * @phpstan-type SourceType resource|string|\SplFileInfo
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
