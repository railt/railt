<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Storage\Drivers;

use Psr\SimpleCache\CacheInterface;
use Railt\Io\Readable;
use Railt\Storage\Storage;

/**
 * Class Psr16Storage
 */
class Psr16Storage implements Storage
{
    private const DEFAULT_REMEMBER_TIME = 60 * 5;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var CacheInterface
     */
    private $storage;

    /**
     * Psr16Storage constructor.
     *
     * @param CacheInterface $storage
     * @param int $timeout
     */
    public function __construct(CacheInterface $storage, int $timeout = self::DEFAULT_REMEMBER_TIME)
    {
        $this->storage = $storage;
        $this->timeout = $timeout;
    }

    /**
     * @param Readable $readable
     * @param \Closure $then
     * @return mixed
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function remember(Readable $readable, \Closure $then)
    {
        $data = $this->storage->get($readable->getHash());

        // If entity exists
        if (\is_object($data)) {
            return $data;
        }

        $result = $then($readable);

        $this->storage->set($readable->getHash(), $data, $this->timeout);

        return $result;
    }
}
