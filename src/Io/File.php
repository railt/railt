<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io;

use Railt\Io\Exceptions\NotFoundException;
use Railt\Io\Exceptions\NotReadableException;

/**
 * Class File
 */
class File implements Writable
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
     * @var string
     */
    protected $hash;

    /**
     * @var Declaration
     */
    private $declaration;

    /**
     * File constructor.
     * @param string $contents
     * @param string $name
     */
    public function __construct(string $contents, string $name = null)
    {
        $this->declaration = Declaration::make(Readable::class);
        $this->contents    = $contents;
    }

    /**
     * @param \SplFileInfo $info
     * @return File
     * @throws \InvalidArgumentException
     */
    public static function fromSplFileInfo(\SplFileInfo $info): Writable
    {
        return static::fromPathname($info->getPathname());
    }

    /**
     * @param string $path
     * @return Writable
     */
    public static function fromPathname(string $path): Writable
    {
        if (! \is_file($path)) {
            throw NotFoundException::fromFilePath($path);
        }

        if (! \is_readable($path)) {
            throw NotReadableException::fromFilePath(\realpath($path));
        }

        $contents = \file_get_contents($path);

        if ($contents === false) {
            throw new NotReadableException('Can not read the file ' . $path);
        }

        return new static($contents, $path);
    }

    /**
     * @param string $sources
     * @param string|null $name
     * @return VirtualFile
     */
    public static function fromSources(string $sources, string $name = null): VirtualFile
    {
        return new VirtualFile($sources, $name);
    }

    /**
     * @param Readable $readable
     * @return VirtualFile
     */
    public static function fromReadable(Readable $readable): self
    {
        $class = $readable->isFile() ? static::class : VirtualFile::class;

        return new $class($readable->getContents(), $readable->getPathname());
    }

    /**
     * @param string $content
     * @return Writable
     */
    public function update(string $content): Writable
    {
        \file_put_contents($this->getPathname(), $content);

        return new static($content, $this->name);
    }

    /**
     * @return string
     */
    public function getPathname(): string
    {
        return $this->name;
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
    protected function createHash(): string
    {
        if ($this->isFile()) {
            return \md5_file($this->getPathname());
        }

        return \md5($this->getContents());
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return \is_file($this->getPathname());
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @param int $bytesOffset
     * @return Position
     */
    public function getPosition(int $bytesOffset): Position
    {
        return new Position($this->getContents(), $bytesOffset);
    }

    /**
     * @return Declaration
     */
    public function getDeclaration(): Declaration
    {
        return $this->declaration;
    }

    /**
     * @return void
     */
    public function __wakeup()
    {
        $this->declaration = Declaration::make(static::class);
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
}
