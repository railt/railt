<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Persisting;

use Cache\Adapter\Common\Exception\CachePoolException;
use Psr\SimpleCache\CacheInterface;
use Railt\Compiler\Exceptions\CompilerException;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Filesystem\ReadableInterface;

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
    private $timeout;

    /**
     * @var CacheInterface
     */
    private $storage;

    /**
     * Psr16Persister constructor.
     * @param CacheInterface $storage
     * @param int $timeout
     */
    public function __construct(CacheInterface $storage, int $timeout = self::DEFAULT_REMEMBER_TIME)
    {
        $this->storage = $storage;
        $this->timeout = $timeout;
    }

    /**
     * @param ReadableInterface $readable
     * @param \Closure $then
     * @return Document
     * @throws \Exception
     * @throws CompilerException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function remember(ReadableInterface $readable, \Closure $then): Document
    {
        $document = $this->storage->get($readable->getHash());

        // If entity exists
        if ($document instanceof Document) {
            return $document;
        }

        $callee = function (Document $document) use ($readable): void {
            try {
                $this->storage->set($readable->getHash(), $document, $this->timeout);
            } catch (CachePoolException $error) {
                throw $error->getPrevious();
            }
        };

        return \tap($then($readable), $callee);
    }
}
