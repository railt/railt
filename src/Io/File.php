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
abstract class File implements Readable
{
    /**
     * @var string
     */
    protected $contents;

    /**
     * @var string
     */
    protected $name;

    /**
     * File constructor.
     *
     * @param string $contents
     * @param string $name
     */
    public function __construct(string $contents, string $name)
    {
        $this->name = $name;
        $this->contents = $contents;
    }

    /**
     * @param \SplFileInfo $info
     * @return Readable|$this
     * @throws NotReadableException
     */
    public static function fromSplFileInfo(\SplFileInfo $info): Readable
    {
        return static::fromPathname($info->getPathname());
    }

    /**
     * @param string $path
     * @return Readable|$this
     * @throws NotReadableException
     */
    public static function fromPathname(string $path): Readable
    {
        return new Physical(self::tryRead($path), \realpath($path));
    }

    /**
     * @param string $path
     * @return string
     * @throws NotFoundException
     * @throws NotReadableException
     */
    private static function tryRead(string $path): string
    {
        self::assertExisting($path);
        self::assertReadable($path);

        $level = \error_reporting(0);
        $contents = @\file_get_contents($path);
        \error_reporting($level);

        if ($contents === false) {
            throw new NotReadableException(\error_get_last()['message']);
        }

        return (string)$contents;
    }

    /**
     * @param string $path
     * @throws NotFoundException
     */
    private static function assertExisting(string $path): void
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
    private static function assertReadable(string $path): void
    {
        if (! \is_readable($path)) {
            $error = 'Can not read the file "%s": Permission denied';
            throw new NotReadableException(\sprintf($error, \realpath($path)));
        }
    }

    /**
     * @param string $fileOrSources
     * @param string|null $name
     * @return Readable|$this
     * @throws NotReadableException
     */
    public static function new(string $fileOrSources, string $name = null): Readable
    {
        if (self::isPhysicallyFile($fileOrSources)) {
            return static::fromPathname($fileOrSources);
        }

        return static::fromSources($fileOrSources, $name);
    }

    /**
     * @param string $path
     * @return bool
     */
    private static function isPhysicallyFile(string $path): bool
    {
        return \is_file($path) && \is_readable($path);
    }

    /**
     * @param string $sources
     * @param string|null $name
     * @return Readable|$this
     */
    public static function fromSources(string $sources = '', string $name = null): Readable
    {
        return $name && \is_file($name) ? new Physical($sources, $name) : new Virtual($sources, $name);
    }

    /**
     * @param string|null $name
     * @return Readable|$this
     */
    public static function empty(string $name = null): Readable
    {
        return static::fromSources('', $name);
    }

    /**
     * @param Readable $readable
     * @return Readable|$this
     */
    public static function fromReadable(Readable $readable): Readable
    {
        return clone $readable;
    }

    /**
     * @param int $bytesOffset
     * @return PositionInterface
     */
    public function getPosition(int $bytesOffset): PositionInterface
    {
        return new Position($this->getContents(), $bytesOffset);
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return [
            'contents',
            'name',
            'hash',
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getPathname();
    }

    /**
     * @return string
     */
    public function getPathname(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        $contents = \substr($this->getContents(), 0, 80);

        return [
            'path'     => $this->getPathname(),
            'contents' => \str_replace("\n", '\n', $contents . '...'),
        ];
    }
}
