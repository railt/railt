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
 * Class EmulatingStorage
 */
class EmulatingStorage implements Storage
{
    /**
     * @var array|string[]
     */
    private $storage = [];

    /**
     * @param Readable $readable
     * @param \Closure $then
     * @return object|mixed
     * @throws \BadMethodCallException
     */
    public function remember(Readable $readable, \Closure $then)
    {
        $key = $readable->getHash();

        if (! \array_key_exists($key, $this->storage)) {
            $this->storage[$key] = $this->encode($then($readable));
        }

        return $this->decode($this->storage[$key]);
    }

    /**
     * @param mixed $data
     * @return string
     * @throws \BadMethodCallException
     */
    private function encode($data): string
    {
        try {
            return \serialize($data);
        } catch (\Error $e) {
            $error = \sprintf('Error while entity serializing: %s', $e->getMessage());
            throw new \BadMethodCallException($error, $e->getCode(), $e);
        }
    }

    /**
     * @param string $data
     * @return mixed
     */
    private function decode(string $data)
    {
        return \unserialize($data, [
            'allowed_classes' => true,
        ]);
    }
}
