<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Compiler\Persisting;

use Cache\Adapter\Common\Exception\CachePoolException;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Railt\Parser\Exceptions\CompilerException;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Exceptions\BuildingException;
use Railt\Support\Filesystem\ReadableInterface;

/**
 * Class Psr6Persister
 */
class Psr6Persister implements Persister
{
    /**
     *
     */
    private const DEFAULT_REMEMBER_TIME = 60 * 5;

    /**
     * @var int
     */
    private $timeout = self::DEFAULT_REMEMBER_TIME;

    /**
     * @var CacheItemPoolInterface
     */
    private $pool;

    /**
     * @var \Closure
     */
    private $persist;

    /**
     * PsrCachePersister constructor.
     * @param CacheItemPoolInterface $pool
     * @param \Closure $persist
     */
    public function __construct(CacheItemPoolInterface $pool, \Closure $persist)
    {
        $this->pool = $pool;
        $this->persist = $persist;
    }

    /**
     * @param int $seconds
     * @return $this
     */
    public function seconds(int $seconds): self
    {
        $this->timeout = $seconds;

        return $this;
    }

    /**
     * @param int $minutes
     * @return $this
     */
    public function minutes(int $minutes): self
    {
        return $this->seconds($minutes * 60);
    }

    /**
     * @param ReadableInterface $readable
     * @param \Closure $then
     * @return Document
     * @throws \Railt\Reflection\Exceptions\BuildingException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \LogicException
     */
    public function remember(ReadableInterface $readable, \Closure $then): Document
    {
        $exists = $this->pool->hasItem($readable->getHash());

        if ($exists) {
            return $this->restore($readable);
        }

        return $this->store($readable, $then($readable));
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
     * @param ReadableInterface $readable
     * @param Document $document
     * @return Document
     * @throws \Exception
     * @throws BuildingException
     * @throws CompilerException
     */
    private function store(ReadableInterface $readable, Document $document): Document
    {
        try {
            /** @var CacheItemInterface $item */
            $item = ($this->persist)($readable, $document);
            $this->touch($item);
            $this->pool->save($item);
        } catch (CachePoolException $e) {
            throw $e->getPrevious();
        } catch (BuildingException $e) {
            throw $e;
        } catch (\Throwable $fatal) {
            throw new CompilerException($fatal->getMessage(), $fatal->getCode(), $fatal);
        }

        return $document;
    }

    /**
     * @param ReadableInterface $readable
     * @return Document
     * @throws CompilerException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function restore(ReadableInterface $readable): Document
    {
        try {
            return $this->touch($this->pool->getItem($readable->getHash()))->get();
        } catch (\Throwable $fatal) {
            throw new CompilerException($fatal->getMessage(), $fatal->getCode(), $fatal);
        }
    }
}
