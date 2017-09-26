<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Railt\Parser\Exceptions\CompilerException;
use Railt\Reflection\Builder\Support\Compilable;
use Railt\Reflection\Contracts\Document;
use Railt\Support\Filesystem\ReadableInterface;

/**
 * Class CachedCompiler
 */
class CachedCompiler extends Compiler
{
    /**
     * Default storage remember timeout: 5 minutes
     */
    private const DEFAULT_PERSISTING_SECONDS = 60 * 5;

    /**
     * @var int
     */
    private $expiresSeconds = self::DEFAULT_PERSISTING_SECONDS;

    /**
     * @var CacheItemPoolInterface
     */
    private $storage;

    /**
     * @var \Closure
     */
    private $persister;

    /**
     * CachedCompiler constructor.
     * @param CacheItemPoolInterface $pool
     * @param \Closure $persister
     * @throws \Railt\Parser\Exceptions\InitializationException
     */
    public function __construct(CacheItemPoolInterface $pool, \Closure $persister)
    {
        $this->storage = $pool;
        $this->persister = $persister;

        parent::__construct();
    }

    /**
     * @param ReadableInterface $readable
     * @return Document
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function compile(ReadableInterface $readable): Document
    {
        $result = $this->cached($readable, function (ReadableInterface $readable) {
            return parent::compile($readable);
        });

        $result->compiler = $this;

        return $result;
    }

    /**
     * @param ReadableInterface $readable
     * @param \Closure $otherwise
     * @return Document
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function cached(ReadableInterface $readable, \Closure $otherwise): Document
    {
        $exists = $this->storage->hasItem($readable->getHash());

        if ($exists) {
            return $this->restore($readable);
        }

        return $this->store($readable, $otherwise);
    }

    /**
     * @param ReadableInterface $readable
     * @param \Closure $otherwise
     * @return Document
     * @throws \Railt\Parser\Exceptions\CompilerException
     */
    private function store(ReadableInterface $readable, \Closure $otherwise): Document
    {
        try {
            /** @var Document $document */
            $document = $otherwise($readable);

            $this->forceBuildTheDocument($document);

            $data = ($this->persister)($readable, $document);

            $this->storage->save($data);

        } catch (\Throwable $error) {
            throw new CompilerException($error->getMessage(), $error->getCode(), $error);
        } finally {
            return $document;
        }
    }

    /**
     * @param ReadableInterface $readable
     * @return Document
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function restore(ReadableInterface $readable): Document
    {
        $item = $this->storage->getItem($readable->getHash());

        $this->touch($item);

        return $item->get();
    }

    /**
     * @param CacheItemInterface $item
     * @return void
     */
    private function touch(CacheItemInterface $item): void
    {
        $item->expiresAfter(\time() + $this->expiresSeconds);
    }

    /**
     * @param Document $document
     * @return bool
     */
    private function forceBuildTheDocument(Document $document): bool
    {
        if ($document instanceof Compilable) {
            return $document->compileIfNotCompiled();
        }

        $types = $document->getTypes();

        foreach ($types as $type) {
            if ($type instanceof Compilable) {
                $type->compileIfNotCompiled();
            }
        }

        return true;
    }
}
