<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Storage\Drivers;

use Psr\Cache\CacheException;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Railt\Io\Readable;
use Railt\Storage\Storage;

/**
 * Class Psr6Storage
 */
class Psr6Storage implements Storage
{
    private const DEFAULT_REMEMBER_TIME = 60 * 5;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var CacheItemPoolInterface
     */
    private $pool;

    /**
     * @var \Closure
     */
    private $persist;

    /**
     * Psr6Storage constructor.
     * @param CacheItemPoolInterface $pool
     * @param \Closure $persist
     * @param int $timeout
     */
    public function __construct(
        CacheItemPoolInterface $pool,
        \Closure $persist,
        int $timeout = self::DEFAULT_REMEMBER_TIME
    ) {
        $this->pool = $pool;
        $this->persist = $persist;
        $this->timeout = $timeout;
    }

    /**
     * @param Readable $readable
     * @param \Closure $then
     * @return object|mixed
     * @throws \InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function remember(Readable $readable, \Closure $then)
    {
        $exists = $this->pool->hasItem($readable->getHash());

        if ($exists) {
            return $this->restore($readable);
        }

        return $this->store($readable, $then($readable));
    }

    /**
     * @param Readable $readable
     * @return object|mixed
     * @throws \InvalidArgumentException
     */
    private function restore(Readable $readable)
    {
        try {
            $result = $this->pool->getItem($readable->getHash());

            return $this->touch($result)->get();
        } catch (InvalidArgumentException | \Throwable $fatal) {
            throw new \InvalidArgumentException($fatal->getMessage(), 0, $fatal);
        }
    }

    /**
     * @param CacheItemInterface $item
     * @return CacheItemInterface
     */
    private function touch(CacheItemInterface $item): CacheItemInterface
    {
        return $item->expiresAfter(\time() + $this->timeout);
    }

    /**
     * @param Readable $readable
     * @param mixed $data
     * @return mixed
     * @throws \Exception
     */
    private function store(Readable $readable, $data)
    {
        try {
            /** @var CacheItemInterface $item */
            $item = ($this->persist)($readable, $data);
            $this->touch($item);
            $this->pool->save($item);
        } catch (CacheException $error) {
            throw $error->getPrevious();
        }

        return $data;
    }
}
