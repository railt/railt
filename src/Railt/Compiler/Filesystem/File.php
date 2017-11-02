<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Filesystem;

use Illuminate\Contracts\Support\Arrayable;
use Railt\Compiler\Exceptions\NotFoundException;
use Railt\Compiler\Exceptions\NotReadableException;

/**
 * Class File
 */
class File implements ReadableInterface, Arrayable
{
    /**
     * @var string
     */
    protected $sources;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var bool
     */
    protected $virtual;

    /**
     * @var null|string
     */
    protected $hash;

    /**
     * @var string
     */
    protected $definitionFile;

    /**
     * @var int
     */
    protected $definitionLine;

    /**
     * File constructor.
     * @param string $sources
     * @param string $name
     * @param bool $virtual
     */
    public function __construct(string $sources, ?string $name, bool $virtual = true)
    {
        [$this->definitionFile, $this->definitionLine] = $this->getBacktrace();

        $this->path    = $name ?? $this->definitionFile;
        $this->sources = $sources;
        $this->virtual = $virtual;
    }

    /**
     * @return array
     */
    private function getBacktrace(): array
    {
        $trace = \array_reverse(\debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        foreach ($trace as $data) {
            $found = \is_subclass_of($data['class'] ?? \stdClass::class, ReadableInterface::class);

            if ($found) {
                return [
                    $data['file'],
                    $data['line'],
                ];
            }
        }

        return ['undefined', 0];
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
     * @return File|ReadableInterface
     * @throws NotReadableException
     */
    public static function fromSplFileInfo(\SplFileInfo $file): ReadableInterface
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
     * @return File|ReadableInterface
     * @throws NotReadableException
     */
    public static function fromPathname(string $path): ReadableInterface
    {
        return static::fromSplFileInfo(new \SplFileInfo($path));
    }

    /**
     * @param string $sources
     * @param null|string $path
     * @return File|ReadableInterface
     */
    public static function fromSources(string $sources, string $path = null): ReadableInterface
    {
        return new static($sources, $path, true);
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return ! $this->virtual;
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->sources;
    }

    /**
     * @return int
     */
    public function getDefinitionLine(): int
    {
        return $this->definitionLine;
    }

    /**
     * @return string
     */
    public function getDefinitionFileName(): string
    {
        return $this->definitionFile;
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
    private function createHash(): string
    {
        if ($this->virtual) {
            return \md5($this->sources);
        }

        return \md5_file($this->getPathname());
    }

    /**
     * @return string
     */
    public function getPathname(): string
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return [
            'hash',
            'path',
            'virtual',
            'sources',
            'definitionFile',
            'definitionLine',
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'path'           => $this->path,
            'definitionFile' => $this->definitionFile,
            'definitionLine' => $this->definitionLine,
        ];
    }
}
