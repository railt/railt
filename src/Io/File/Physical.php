<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io\File;

use Railt\Io\File;

/**
 * Class Physical
 */
class Physical extends File
{
    /**
     * @var string|null
     */
    private $hash;

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
        return \sha1($this->getPathname() . ':' . \filemtime($this->getPathname()));
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return true;
    }
}
