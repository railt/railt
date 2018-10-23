<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Storage\Drivers;

use Railt\Io\Readable;
use Railt\Storage\Storage;

/**
 * Class ArrayStorage
 */
class ArrayStorage implements Storage
{
    /**
     * @var array|object[]
     */
    private $storage = [];

    /**
     * @param Readable $readable
     * @param \Closure $then
     * @return object|mixed
     */
    public function remember(Readable $readable, \Closure $then)
    {
        $key = $readable->getHash();

        if (! \array_key_exists($key, $this->storage)) {
            $this->storage[$key] = $then($readable);
        }

        return $this->storage[$key];
    }
}
