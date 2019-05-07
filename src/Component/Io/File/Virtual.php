<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Io\File;

/**
 * Class Virtual
 */
class Virtual extends AbstractFile
{
    /**
     * @var string A default file name which created from sources
     */
    public const DEFAULT_FILE_NAME = 'php://input';

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string|null
     */
    protected $hash;

    /**
     * Virtual constructor.
     *
     * @param string $content
     * @param string|null $name
     */
    public function __construct(string $content, string $name = null)
    {
        $this->content = $content;

        parent::__construct($name ?? self::DEFAULT_FILE_NAME);
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return \is_file($this->getPathname());
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'hash',
            'content',
        ]);
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->content;
    }

    /**
     * @param bool $exclusive
     * @return resource
     */
    public function getStreamContents(bool $exclusive = false)
    {
        $stream = \fopen('php://memory', 'rb+');

        \fwrite($stream, $this->getContents());
        \rewind($stream);

        return $stream;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        if ($this->hash === null) {
            $this->hash = \sha1($this->getPathname() . ':' . $this->content);
        }

        return $this->hash;
    }
}
