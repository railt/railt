<?php

declare(strict_types=1);

namespace Railt\SDL\Parser;

use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\File;
use Psr\SimpleCache\CacheInterface;
use Railt\SDL\Node\Node;

final class CachedParser implements ParserInterface
{
    /**
     * @var non-empty-string
     */
    private const DEFAULT_CACHE_PREFIX = 'railt.ast.';

    public function __construct(
        private readonly CacheInterface $cache,
        private readonly ParserInterface $parent,
        private readonly string $prefix = self::DEFAULT_CACHE_PREFIX,
    ) {}

    /**
     * @return non-empty-string
     */
    private function getKey(ReadableInterface $source): string
    {
        return $this->prefix
            . \hash('xxh64', $source->getHash())
        ;
    }

    public function parse($source): iterable
    {
        $key = $this->getKey($source = File::new($source));

        if ($this->cache->has($key)) {
            /** @var iterable<Node> */
            return (array)$this->cache->get($key);
        }

        /** @var iterable<Node> $result */
        $result = $this->parent->parse($source);

        $this->cache->set($key, $result);

        return $result;
    }
}
