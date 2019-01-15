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
use Railt\Io\Readable;
use Railt\SDL\Compiler;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Schema\CompilerInterface;
use Railt\Storage\Drivers\ArrayStorage;
use Railt\Storage\Drivers\EmulatingStorage;
use Railt\Storage\Drivers\NullableStorage;
use Railt\Storage\Drivers\Psr16Storage;
use Railt\Storage\Drivers\Psr6Storage;
use Railt\Storage\Storage;

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
    public function providerCompilers(): array
    {
        $result = [];

        foreach ($this->getCompilers() as $compiler) {
            $result[] = [$compiler];
        }

        return $result;
    }

    /**
     * @return \Generator|Compiler[]|CompilerInterface[]
     * @throws \Exception
     */
    protected function getCompilers(): \Traversable
    {
        $storage = __DIR__ . '/temp/';

        // Default
        yield new Compiler();

        // Nullable (Return Document "as is")
        yield new Compiler(new NullableStorage());

        // Array (Return Document "as is" and store same files into php array stateless memory)
        yield new Compiler(new ArrayStorage());

        // Emulation of data saving
        yield new Compiler(new EmulatingStorage());

        // PSR-6 + Flysystem Serialization
        yield new Compiler($this->getPsr6FileSystemStorage($storage));

        // PSR-16 + system Serialization
        yield new Compiler($this->getPsr16FileSystemStorage($storage));
    }

    /**
     * @param string $dir
     * @return Storage
     * @throws \Exception
     */
    private function getPsr6FileSystemStorage(string $dir): Storage
    {
        $cachePool = $this->createFilesystemPool('psr6', $dir);

        return new Psr6Storage($cachePool, function (Readable $readable, Document $document) {
            return new CacheItem($readable->getHash(), true, $document);
        });
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

        $folder = \snake_case(\class_basename($this)) .
            '/' . $name . '_' . \random_int(0, \PHP_INT_MAX);

        return new FilesystemCachePool($filesystem, $folder);
    }

    /**
     * @param string $dir
     * @return Storage
     * @throws \Exception
     */
    private function getPsr16FileSystemStorage(string $dir): Storage
    {
        return new Psr16Storage($this->createFilesystemPool('psr16', $dir));
    }
}
