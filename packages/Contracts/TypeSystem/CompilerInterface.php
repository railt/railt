<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\TypeSystem;

use Phplrt\Contracts\Source\ReadableInterface;

/**
 * Interface CompilerInterface
 */
interface CompilerInterface
{
    /**
     * @param ReadableInterface|string|resource|mixed $source
     * @param array|string[]|null $typeNames
     * @return DocumentInterface
     */
    public function compile($source, array $typeNames = null): DocumentInterface;

    /**
     * @param ReadableInterface|string|resource|mixed $source
     * @param array|string[]|null $typeNames
     * @return void
     */
    public function preload($source, array $typeNames = null): void;
}
