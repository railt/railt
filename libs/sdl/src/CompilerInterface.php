<?php

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Contracts\Source\ReadableInterface;

interface CompilerInterface extends TypeLoaderInterface
{
    /**
     * @param string|resource|\SplFileInfo|ReadableInterface $source
     */
    public function load(mixed $source): DictionaryInterface;

    /**
     * @param string|resource|\SplFileInfo|ReadableInterface $source
     */
    public function compile(mixed $source): DictionaryInterface;
}
