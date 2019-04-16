<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Io;

use Railt\Component\Position\PositionInterface;

/**
 * Interface Readable
 */
interface Readable
{
    /**
     * Returns the path to the file.
     *
     * @return string
     */
    public function getPathname(): string;

    /**
     * Returns the hash of the file.
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
     * Returns content stream
     *
     * @param bool $exclusive Exclusive access to the file means that it
     *      cannot be accessed by other programs while reading the sources.
     * @return resource
     */
    public function getStreamContents(bool $exclusive = false);

    /**
     * Returns a position in the source text by offset in bytes.
     *
     * @param int $offset
     * @return PositionInterface
     */
    public function getPosition(int $offset): PositionInterface;

    /**
     * @deprecated Use method "exists()" instead.
     * @return bool
     */
    public function isFile(): bool;

    /**
     * Returns information whether the file actually exists on the file system.
     *
     * @return bool
     */
    public function exists(): bool;
}
