<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Filesystem;

/**
 * The interface that defines the source of the code.
 */
interface ReadableInterface
{
    /**
     * Returns the path to the file.
     *
     * @return string
     */
    public function getPathname(): string;

    /**
     * Returns the hash of the file. Required for
     * disability cache.
     *
     * @return string
     */
    public function getHash(): string;

    /**
     * Returns the full contents of the source.
     *
     * @return string
     */
    public function getContents(): string;

    /**
     * Returns the line where this implementation
     * was defined. Required for errors debugging.
     *
     * @return int
     */
    public function getDefinitionLine(): int;

    /**
     * Returns the path and file where this implementation
     * was defined. Required for errors debugging.
     *
     * @return string
     */
    public function getDefinitionFileName(): string;

    /**
     * @return bool
     */
    public function isFile(): bool;
}
