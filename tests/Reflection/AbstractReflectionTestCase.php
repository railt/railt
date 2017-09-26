<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Cache\Adapter\Common\CacheItem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Railt\Reflection\CachedCompiler;
use Railt\Reflection\Compiler;
use Railt\Reflection\Contracts;
use Railt\Support\Filesystem\File;
use Railt\Support\Filesystem\ReadableInterface;
use Railt\Tests\AbstractTestCase;

/**
 * Class AbstractReflectionTestCase
 * @package Railt\Tests
 */
abstract class AbstractReflectionTestCase extends AbstractTestCase
{
    /**
     * @param string $body
     * @return Contracts\Document
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    protected function getDocument(string $body): Contracts\Document
    {
        $readable = File::fromSources($body);

        return (new Compiler())->compile($readable);
    }

    /**
     * @param string $body
     * @return Contracts\Document
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    protected function getCachedDocument(string $body): Contracts\Document
    {
        $driver = new Local(__DIR__ . '/../.temp/');
        $fs = new Filesystem($driver);
        $pool = new FilesystemCachePool($fs, \date('YmdHis'));

        $loader = function (ReadableInterface $readable, Contracts\Document $document) {
            return new CacheItem($readable->getHash(), true, $document);
        };

        return (new CachedCompiler($pool, $loader))->compile(File::fromSources($body));
    }
}
