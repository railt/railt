<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Io;

/**
 * Class VirtualFile
 */
class VirtualFile extends File
{
    /**
     * @var string
     */
    public const DEFAULT_FILE_NAME = 'php://input';

    /**
     * VirtualFile constructor.
     * @param string $contents
     * @param string $name
     */
    public function __construct(string $contents, string $name = null)
    {
        parent::__construct($contents, $name ?: self::DEFAULT_FILE_NAME);
    }

    /**
     * @param string $content
     * @return static
     */
    public static function fromSources(string $content): self
    {
        return new static($content);
    }

    /**
     * @param Readable $readable
     * @return VirtualFile
     */
    public static function fromReadable(Readable $readable): self
    {
        return new static($readable->getContents(), $readable->getPathname());
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return \md5($this->getContents());
    }
}
