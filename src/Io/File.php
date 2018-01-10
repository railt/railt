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
use Railt\Io\Utils\ErrorHelper;
use Railt\Io\Utils\TraceHelper;

/**
 * Class File
 */
class File implements Writable, Traceable
{
    use ErrorHelper;
    use TraceHelper;

    /**
     * @var string
     */
    private $contents;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    protected $hash;

    /**
     * File constructor.
     * @param string $contents
     * @param string $name
     */
    public function __construct(string $contents, string $name = null)
    {
        [$this->definitionFile, $this->definitionLine, $this->definitionClass] = $this->getBacktrace();

        $this->contents = $contents;
        $this->name     = ($name ?? $this->definitionClass) ?? $this->definitionFile;
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
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
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
}
