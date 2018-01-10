<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io;

/**
 * Class VirtualFile
 */
class VirtualFile extends File
{
    /**
     * @param string $content
     * @return Writable
     */
    public function update(string $content): Writable
    {
        return new static($content, $this->getPathname());
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        if ($this->hash === null) {
            $this->hash = \md5($this->getContents());
        }

        return $this->hash;
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return false;
    }
}
