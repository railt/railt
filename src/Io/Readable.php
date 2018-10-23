<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io;

use Railt\Io\Exception\ExternalExceptionInterface;

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
     * @param int $bytesOffset
     * @return PositionInterface
     */
    public function getPosition(int $bytesOffset): PositionInterface;

    /**
     * @return DeclarationInterface
     */
    public function getDeclarationInfo(): DeclarationInterface;

    /**
     * @param string $message
     * @param int $offsetOrLine
     * @param int|null $column
     * @return ExternalExceptionInterface
     */
    public function error(string $message, int $offsetOrLine = 0, int $column = null): ExternalExceptionInterface;

    /**
     * @return bool
     */
    public function isFile(): bool;
}
