<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Filesystem;

use Railt\Compiler\Exceptions\NotFoundException;
use Railt\Compiler\Exceptions\NotReadableException;

/**
 * Class File
 */
class File implements ReadableInterface
{
    /**
     * @var string
     */
    private $sources;

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $virtual;

    /**
     * @var null|string
     */
    private $hash;

    /**
     * File constructor.
     * @param string $sources
     * @param string $path
     * @param bool $virtual
     */
    public function __construct(string $sources, ?string $path, bool $virtual = true)
    {
        $this->path    = $path ?? static::VIRTUAL_FILE_NAME;
        $this->sources = $sources;
        $this->virtual = $virtual;
    }

    /**
     * @return string
     */
    public function getPathname(): string
    {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return !$this->virtual;
    }

    /**
     * @param string|\SplFileInfo $file
     * @return File
     * @throws \InvalidArgumentException
     * @throws NotReadableException
     */
    public static function new($file): File
    {
        if ($file instanceof \SplFileInfo) {
            return static::fromSplFileInfo($file);
        }

        if (! \is_string($file)) {
            throw new \InvalidArgumentException('File name must be a string.');
        }

        if (\is_file($file)) {
            return static::fromPathname($file);
        }

        return static::fromSources($file);
    }

    /**
     * @param \SplFileInfo $file
     * @return File
     * @throws NotReadableException
     */
    public static function fromSplFileInfo(\SplFileInfo $file): File
    {
        if (! \is_file($file->getPathname())) {
            throw new NotFoundException($file->getPathname());
        }

        if (! $file->isReadable()) {
            throw new NotReadableException($file->getPathname());
        }

        $sources = @\file_get_contents($file->getPathname());

        return new static($sources, $file->getPathname(), false);
    }

    /**
     * @param string $path
     * @return File
     * @throws NotReadableException
     */
    public static function fromPathname(string $path): File
    {
        return static::fromSplFileInfo(new \SplFileInfo($path));
    }

    /**
     * @param string $sources
     * @param null|string $path
     * @return File
     */
    public static function fromSources(string $sources, string $path = null): File
    {
        return new static($sources, $path, true);
    }

    /**
     * @return string
     */
    public function read(): string
    {
        return $this->sources;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        if ($this->hash === null) {
            $this->hash = $this->createHash();
        }

        return $this->hash;
    }

    /**
     * @return string
     */
    public function rehash(): string
    {
        $this->hash = null;
        return $this->getHash();
    }

    /**
     * @return string
     */
    private function createHash(): string
    {
        if ($this->virtual) {
            return \md5($this->sources);
        }

        return \md5_file($this->getPathname());
    }
}
