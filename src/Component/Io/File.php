<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Io;

use Railt\Component\Io\File\Virtual;
use Railt\Component\Io\File\Physical;
use Railt\Component\Io\Exception\NotFoundException;
use Railt\Component\Io\Exception\NotReadableException;

/**
 * Class File
 */
final class File implements FactoryInterface
{
    /**
     * File constructor.
     */
    private function __construct()
    {
        throw new \LogicException('File factory is not instantiable');
    }

    /**
     * @param \SplFileInfo $info
     * @return Readable
     * @throws NotReadableException
     */
    public static function fromSplFileInfo(\SplFileInfo $info): Readable
    {
        return static::fromPathname($info->getPathname());
    }

    /**
     * @param string $path
     * @return Readable
     * @throws NotFoundException
     * @throws NotReadableException
     */
    public static function fromPathname(string $path): Readable
    {
        return new Physical($path);
    }

    /**
     * @param string $sources
     * @param string|null $name
     * @return Readable
     */
    public static function fromSources(string $sources = '', string $name = null): Readable
    {
        return new Virtual($sources, $name);
    }

    /**
     * @param string|null $name
     * @return Readable
     */
    public static function empty(string $name = null): Readable
    {
        return new Virtual('', $name);
    }

    /**
     * @param Readable $readable
     * @return Readable|$this
     */
    public static function fromReadable(Readable $readable): Readable
    {
        return clone $readable;
    }
}
