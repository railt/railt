<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator;

use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class GeneratedResult
 */
class GeneratedResult
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $file;

    /**
     * ClassDefinition constructor.
     * @param string $body
     * @param string $class
     */
    public function __construct(string $body, string $class)
    {
        $this->body = $body;
        $this->as($class . '.php');
    }

    /**
     * @param string $filename
     * @return GeneratedResult
     */
    public function as(string $filename): GeneratedResult
    {
        $this->file = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function read(): string
    {
        return $this->body;
    }

    /**
     * @param string $directory
     * @return Readable
     * @throws \Railt\Io\Exceptions\NotReadableException
     * @throws \RuntimeException
     */
    public function saveTo(string $directory): Readable
    {
        $path = $directory . \DIRECTORY_SEPARATOR . $this->file;

        if (\is_file($path) && ! @\unlink($path)) {
            throw new \RuntimeException('Could not save a new source into ' . $path);
        }

        if (! @\file_put_contents($path, $this->body)) {
            throw new \RuntimeException('Could not save a new source into ' . $path);
        }

        return File::fromPathname($path);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->body;
    }
}
