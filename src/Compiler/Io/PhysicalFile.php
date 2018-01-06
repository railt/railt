<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Io;

/**
 * Class PhysicalFile
 */
class PhysicalFile extends File
{
    /**
     * @var string|null
     */
    private $hash;

    /**
     * @param string $path
     * @return PhysicalFile
     * @throws \InvalidArgumentException
     */
    public static function fromPathname(string $path): self
    {
        if (! \is_file($path)) {
            throw new \InvalidArgumentException('File ' . $path . ' not exists.');
        }

        if (! \is_readable($path)) {
            throw new \InvalidArgumentException('File ' . \realpath($path) . ' not readable. Permission denied.');
        }

        $contents = \file_get_contents($path);

        return new static($contents, $path);
    }

    /**
     * @param \SplFileInfo $info
     * @return PhysicalFile
     * @throws \InvalidArgumentException
     */
    public static function fromSpl(\SplFileInfo $info): self
    {
        return static::fromPathname($info->getPathname());
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        if ($this->hash === null) {
            $this->hash = \md5(\filemtime($this->getPathname()));
        }

        return $this->hash;
    }

    /**
     * @param string $content
     * @return Writable
     */
    public function update(string $content): Writable
    {
        \file_put_contents($this->getPathname(), $content);

        return parent::update($content);
    }
}
