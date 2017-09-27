<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Compiler\Persisting;

use Psr\SimpleCache\CacheInterface;
use Railt\Parser\Exceptions\CompilerException;
use Railt\Reflection\Contracts\Document;
use Railt\Support\Filesystem\ReadableInterface;

/**
 * Class Psr16Persister
 */
class Psr16Persister implements Persister
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
     * @var CacheInterface
     */
    private $storage;

    /**
     * Psr16Persister constructor.
     * @param CacheInterface $storage
     */
    public function __construct(CacheInterface $storage)
    {
        $this->storage = $storage;
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
     * @throws CompilerException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function remember(ReadableInterface $readable, \Closure $then): Document
    {
        $document = $this->storage->get($readable->getHash());

        if ($document instanceof Document) {
            return $document;
        }

        return \tap($then($readable), function (Document $document) use ($readable): void {
            $this->storage->set($readable->getHash(), $document, $this->timeout);
        });
    }
}
