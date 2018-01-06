<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Filesystem;

use Railt\Compiler\Io\Readable;

/**
 * The interface that defines the source of the code.
 */
interface ReadableInterface extends Readable
{
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
