<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

use Railt\Parser\Exceptions\NotReadableException;

/**
 * Class File
 * @package Railt\Parser
 */
class File
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
     * File constructor.
     * @param string $sources
     * @param string $path
     */
    public function __construct(string $sources, ?string $path)
    {
        $this->sources = $sources;
        $this->path = $path ?? 'php://input';
    }

    /**
     * @param string $path
     * @return File
     * @throws \Railt\Parser\Exceptions\NotReadableException
     */
    public static function make(string $path): File
    {
        if (is_file($path)) {
            return static::path($path);
        }

        return static::virual($path);
    }

    /**
     * @param string $sources
     * @param null|string $path
     * @return static
     */
    public static function virual(string $sources, ?string $path = null)
    {
        return new static($sources, $path);
    }

    /**
     * @param \SplFileInfo $file
     * @return File
     * @throws NotReadableException
     */
    public static function physics(\SplFileInfo $file): File
    {
        if (!$file->isReadable()) {
            throw new NotReadableException($file->getPathname());
        }

        $sources = @file_get_contents($file->getPathname());

        return new static($sources, $file->getPathname());
    }

    /**
     * @param string $path
     * @return File
     * @throws NotReadableException
     */
    public static function path(string $path): File
    {
        return static::physics(new \SplFileInfo($path));
    }

    /**
     * @return string
     */
    public function getSources(): string
    {
        return $this->sources;
    }

    /**
     * @return string
     */
    public function getPathname(): string
    {
        return $this->path;
    }
}
