<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem;

use Phplrt\Contracts\Source\ReadableInterface;
use Railt\TypeSystem\Document\DocumentInterface;
use Railt\TypeSystem\Linker\LinkerInterface;

/**
 * Interface CompilerInterface
 */
interface CompilerInterface
{
    /**
     * @param ReadableInterface|string|resource|mixed $source
     * @param array|string[]|null $types
     * @return DocumentInterface
     */
    public function compile($source, array $types = null): DocumentInterface;

    /**
     * @param ReadableInterface|string|resource|mixed $source
     * @param array|string[]|null $types
     * @return DocumentInterface
     */
    public function preload($source, array $types = null): DocumentInterface;

    /**
     * @param LinkerInterface $linker
     * @return void
     */
    public function autoload(LinkerInterface $linker): void;
}
