<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Source\File;
use Psr\SimpleCache\CacheInterface;
use Phplrt\Contracts\Source\FileInterface;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Phplrt\Contracts\Parser\ParserInterface;
use Railt\SDL\Parser\Parser as ParserRuntime;
use Psr\SimpleCache\InvalidArgumentException;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\Exception\NotAccessibleException;

/**
 * Class Parser
 */
class Parser implements ParserInterface
{
    /**
     * @var ParserInterface|ParserRuntime
     */
    private ParserInterface $parser;

    /**
     * @var CacheInterface|null
     */
    private CacheInterface $cache;

    /**
     * Parser constructor.
     *
     * @param CacheInterface|null $cache
     */
    public function __construct(CacheInterface $cache = null)
    {
        $this->parser = new ParserRuntime();
        $this->cache = $cache ?? new ArrayCachePool();
    }

    /**
     * {@inheritDoc}
     * @throws NotAccessibleException
     * @throws \RuntimeException
     * @throws \Throwable
     * @throws InvalidArgumentException
     */
    public function parse($source): iterable
    {
        $hash = $this->hash($source = File::new($source));

        if (! $this->cache->has($hash)) {
            $this->cache->set($hash, $this->parser->parse($source));
        }

        return $this->cache->get($hash);
    }

    /**
     * @param ReadableInterface $source
     * @return string
     */
    private function hash(ReadableInterface $source): string
    {
        if ($source instanceof FileInterface) {
            return \md5_file($source->getPathname());
        }

        return \md5($source->getContents());
    }


}
