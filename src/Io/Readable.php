<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io;

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
     * @param int $bytesOffset
     * @return Position
     */
    public function getPosition(int $bytesOffset): Position;

    /**
     * @return Declaration
     */
    public function getDeclaration(): Declaration;

    /**
     * @return bool
     */
    public function isFile(): bool;
}
