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
 * Class File
 */
final class File
{
    /**
     * @param \SplFileInfo $info
     * @return File|Readable
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public static function fromSplFileInfo(\SplFileInfo $info): Readable
    {
        if ($info instanceof \Symfony\Component\Finder\SplFileInfo) {
            return new Physical($info->getContents(), $info->getPathname());
        }

        return static::fromPathname($info->getPathname());
    }

    /**
     * @param string $path
     * @return File|Readable
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public static function fromPathname(string $path): Readable
    {
        self::verifyIsFile($path);
        self::verifyIsReadable($path);

        return new Physical(self::tryRead($path), $path);
    }

    /**
     * @param string $path
     * @throws NotFoundException
     */
    private static function verifyIsFile(string $path): void
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
    private static function verifyIsReadable(string $path): void
    {
        if (! \is_readable($path)) {
            $error = 'Can not open the file for reading "%s": Permission denied';
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
        $level    = \error_reporting(0);
        $contents = \file_get_contents($path);

        \error_reporting($level);

        if ($contents === false) {
            $error = 'An unexpected error while reading the file "%s": %s';
            throw new NotReadableException(\sprintf($error, \realpath($path), \error_get_last()['message']));
        }

        return $contents;
    }

    /**
     * @param string $sources
     * @param string|null $name
     * @return Virtual|Readable
     */
    public static function fromSources(string $sources, string $name = null): Readable
    {
        return new Virtual($sources, $name);
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
