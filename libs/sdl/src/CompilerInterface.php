<?php

declare(strict_types=1);

namespace Railt\SDL;

use Railt\TypeSystem\DictionaryInterface;

interface CompilerInterface extends TypeLoaderInterface
{
    /**
     * Returns the current list of registered types.
     */
    public function getTypes(): DictionaryInterface;

    /**
     * Parses the GraphQL SDL source code and loads the recognized types
     * into the dictionary.
     *
     * @param string|resource|\SplFileInfo $source
     * @param array<non-empty-string, mixed> $variables
     */
    public function load(mixed $source, array $variables = []): DictionaryInterface;

    /**
     * Parses the GraphQL SDL source code and returns a list of loaded
     * types for the specified source.
     *
     * @param string|resource|\SplFileInfo $source
     * @param array<non-empty-string, mixed> $variables
     */
    public function compile(mixed $source, array $variables = []): DictionaryInterface;
}
