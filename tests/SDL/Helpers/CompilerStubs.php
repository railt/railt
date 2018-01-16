<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Helpers;

use Cache\Adapter\Common\AbstractCachePool;
use Cache\Adapter\Common\CacheItem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Compiler;
use Railt\SDL\Reflection\CompilerInterface;
use Railt\Storage\ArrayPersister;
use Railt\Storage\EmulatingPersister;
use Railt\Storage\NullablePersister;
use Railt\Storage\Persister;
use Railt\Storage\Psr16Persister;
use Railt\Storage\Psr6Persister;

/**
 * Trait CompilerStubs
 */
trait CompilerStubs
{
    use ParserStubs;

    /**
     * @return array
     * @throws \Exception
     */
    protected function providerCompilers(): array
    {
        return \array_map(function(Compiler $compiler): array {
            return [$compiler];
        }, \iterator_to_array($this->getCompilers()));
    }

    /**
     * @return \Generator|Compiler[]|CompilerInterface[]
     * @throws \Exception
     */
    protected function getCompilers(): \Traversable
    {
        $storage = __DIR__ . '/.temp/';

        // Default
        yield new Compiler(
            null
        );

        // Nullable (Return Document "as is")
        yield new Compiler(
            new NullablePersister()
        );

        // Array (Return Document "as is" and store same files into php array stateless memory)
        yield new Compiler(
            new ArrayPersister()
        );

        // Emulation of data saving
        yield new Compiler(
            new EmulatingPersister()
        );

        // PSR-6 + Flysystem Serialization
        yield new Compiler(
            $this->getPsr6FileSystemPersister($storage)
        );

        // PSR-16 + system Serialization
        yield new Compiler(
            $this->getPsr16FileSystemPersister($storage)
        );
    }

    /**
     * @param string $dir
     * @return Persister
     * @throws \Exception
     */
    private function getPsr6FileSystemPersister(string $dir): Persister
    {
        $cachePool = $this->createFilesystemPool('psr6', $dir);

        return new Psr6Persister($cachePool, function (Readable $readable, Document $document) {
            return new CacheItem($readable->getHash(), true, $document);
        });
    }

    /**
     * @param string $dir
     * @return Persister
     * @throws \Exception
     */
    private function getPsr16FileSystemPersister(string $dir): Persister
    {
        return new Psr16Persister($this->createFilesystemPool('psr16', $dir));
    }

    /**
     * @param string $name
     * @param string $dir
     * @return AbstractCachePool
     * @throws \Exception
     */
    private function createFilesystemPool(string $name, string $dir): AbstractCachePool
    {
        $filesystem = new Filesystem(new Local($dir));

        $folder     = \snake_case(\class_basename($this)) .
            '/' . $name . '_' . \random_int(0, \PHP_INT_MAX);

        return new FilesystemCachePool($filesystem, $folder);
    }
}
