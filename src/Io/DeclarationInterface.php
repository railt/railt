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
 * Information about the location of the file definition.
 * Required for errors debugging.
 */
interface DeclarationInterface
{
    /**
     * Provides the path and file where this implementation was defined.
     *
     * @return string
     */
    public function getPathname(): string;

    /**
     * Provides the line where this implementation was defined.
     *
     * @return int
     */
    public function getLine(): int;

    /**
     * Provides the class name where this implementation was defined.
     *
     * @return null|string
     */
    public function getClass(): ?string;
}
