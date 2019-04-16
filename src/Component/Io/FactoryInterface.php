<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Io;

/**
 * Interface FactoryInterface
 */
interface FactoryInterface
{
    /**
     * @param string $sources
     * @param string|null $name
     * @return Readable
     */
    public static function fromSources(string $sources = '', string $name = null): Readable;

    /**
     * @param string $path
     * @return Readable
     */
    public static function fromPathname(string $path): Readable;

    /**
     * @param \SplFileInfo $info
     * @return Readable
     */
    public static function fromSplFileInfo(\SplFileInfo $info): Readable;

    /**
     * @param string|null $name
     * @return Readable
     */
    public static function empty(string $name = null): Readable;
}
