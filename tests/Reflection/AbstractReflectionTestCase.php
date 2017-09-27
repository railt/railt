<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Cache\Adapter\Common\AbstractCachePool;
use Cache\Adapter\Common\CacheItem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Railt\Reflection\Compiler;
use Railt\Reflection\Compiler\Persisting\ArrayPersister;
use Railt\Reflection\Compiler\Persisting\EmulatingPersister;
use Railt\Reflection\Compiler\Persisting\NullablePersister;
use Railt\Reflection\Compiler\Persisting\Persister;
use Railt\Reflection\Compiler\Persisting\Psr16Persister;
use Railt\Reflection\Compiler\Persisting\Psr6Persister;
use Railt\Reflection\Contracts\Document;
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
     * @return array
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    protected function dataProviderDocuments(string $body): array
    {
        $result = [];

        foreach ($this->getDocuments($body) as $document) {
            $result[] = [$document];
        }

        return $result;
    }

    /**
     * @param string $body
     * @return iterable|Document[]
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    protected function getDocuments(string $body): iterable
    {
        $readable = File::fromSources($body);

        // Default
        yield (new Compiler())->compile($readable);

        // Nullable (Return Document "as is")
        yield (new Compiler(new NullablePersister()))->compile($readable);

        // Array (Return Document "as is" and store same files into php array stateless memory)
        yield (new Compiler(new ArrayPersister()))->compile($readable);

        // Emulation of data saving
        yield (new Compiler(new EmulatingPersister()))->compile($readable);

        // PSR-6 + Flysystem Serialization
        yield (new Compiler($this->getPsr6FileSystemPersister()))->compile($readable);

        // PSR-16 + Filesystem Serialization
        yield (new Compiler($this->getPsr16FileSystemPersister()))->compile($readable);
    }

    /**
     * @return Persister
     */
    private function getPsr6FileSystemPersister(): Persister
    {
        $cachePool = $this->createFilesystemPool('psr6');

        return new Psr6Persister($cachePool, function (ReadableInterface $readable, Document $document) {
            return new CacheItem($readable->getHash(), true, $document);
        });
    }

    /**
     * @param string $name
     * @return AbstractCachePool
     */
    private function createFilesystemPool(string $name): AbstractCachePool
    {
        $filesystem = new Filesystem(new Local(__DIR__ . '/../.temp/'));
        $folder = \snake_case(\class_basename($this)) .
            '/' . $name .
            '/' . \date('m') .
            '/' . \date('s');

        return new FilesystemCachePool($filesystem, $folder);
    }

    /**
     * @return Persister
     */
    private function getPsr16FileSystemPersister(): Persister
    {
        return new Psr16Persister($this->createFilesystemPool('psr16'));
    }
}
