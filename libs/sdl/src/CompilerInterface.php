<?php

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Contracts\Source\ReadableInterface;

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
     * @param string|resource|\SplFileInfo|ReadableInterface $source
     */
    public function load(mixed $source): DictionaryInterface;

    /**
     * Parses the GraphQL SDL source code and returns a list of loaded
     * types for the specified source.
     *
     * @param string|resource|\SplFileInfo|ReadableInterface $source
     */
    public function compile(mixed $source): DictionaryInterface;
}
