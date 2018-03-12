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
class File implements Readable
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
     * @param string|null $contents
     * @param string|null $name
     */
    public function __construct(string $contents = null, string $name = null)
    {
        $this->declaration = Declaration::make(Readable::class);
        $this->contents    = $this->contents ?? $contents ?? '';
        $this->name        = $name ?? 'php://input';
    }

    /**
     * @param \SplFileInfo $info
     * @return File|Readable
     * @throws \Railt\Io\Exceptions\NotReadableException
     * @throws \InvalidArgumentException
     */
    public static function fromSplFileInfo(\SplFileInfo $info): self
    {
        return static::fromPathname($info->getPathname());
    }

    /**
     * @param string $path
     * @return File|Readable
     * @throws \Railt\Io\Exceptions\NotReadableException
     */
    public static function fromPathname(string $path): self
    {
        if (! \is_file($path)) {
            throw NotFoundException::fromFilePath($path);
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
     * @return string
     */
    public function getPathname(): string
    {
        return $this->name;
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
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
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
    public function __wakeup(): void
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

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        $result = ['hash' => $this->getHash()];

        if (! $this->isFile()) {
            $result['content'] = $this->getContents();
        } else {
            $result['path'] = $this->getPathname();
        }

        return $result;
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
        // 1) If is file: Hash of "FILE_PATH:LAST_UPDATE_TIME"
        // 2) Otherwise:  Hash of sources
        $target = $this->isFile()
            ? $this->getPathname() . ':' . \filemtime($this->getPathname())
            : $this->getContents();

        return \sha1($target);
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return \is_file($this->getPathname());
    }
}
