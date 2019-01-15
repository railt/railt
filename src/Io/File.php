<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io;

use Railt\Io\Exception\NotFoundException;
use Railt\Io\Exception\NotReadableException;
use Railt\Io\File\Physical;
use Railt\Io\File\Virtual;

/**
 * File factory.
 */
abstract class File
{
    /**
     * @param \SplFileInfo $info
     * @return File|Readable
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public static function fromSplFileInfo(\SplFileInfo $info): Readable
    {
        return static::fromPathname($info->getPathname());
    }

    /**
     * @param string $path
     * @return File|Readable
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public static function fromPathname(string $path): Readable
    {
        return new Physical(self::tryRead($path), \realpath($path));
    }

    /**
     * @param string $path
     * @throws NotFoundException
     */
    private static function isFile(string $path): void
    {
        if (! \is_file($path)) {
            $error = 'File "%s" not found';
            throw new NotFoundException(\sprintf($error, $path));
        }
    }

    /**
     * @param string $path
     * @throws NotReadableException
     */
    private static function isReadable(string $path): void
    {
        if (! \is_readable($path)) {
            $error = 'Can not read the file "%s": Permission denied';
            throw new NotReadableException(\sprintf($error, \realpath($path)));
        }
    }

    /**
     * @param string $path
     * @return string
     * @throws NotReadableException
     */
    private static function tryRead(string $path): string
    {
        self::isFile($path);
        self::isReadable($path);

        $level = \error_reporting(0);
        $contents = @\file_get_contents($path);
        \error_reporting($level);

        return (string)$contents;
    }

    /**
     * @param string $sources
     * @param string|null $name
     * @return Virtual|Readable
     */
    public static function fromSources(string $sources, string $name = null): Readable
    {
        return $name && \is_file($name)
            ? new Physical($sources, $name)
            : new Virtual($sources, $name);
    }

    /**
     * @param Readable $readable
     * @return Readable
     */
    public static function fromReadable(Readable $readable): Readable
    {
        return clone $readable;
    }
}
