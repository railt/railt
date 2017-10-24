<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Cache\Adapter\Common\AbstractCachePool;
use Cache\Adapter\Common\CacheItem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Railt\Compiler\Compiler;
use Railt\Compiler\Persisting\ArrayPersister;
use Railt\Compiler\Persisting\EmulatingPersister;
use Railt\Compiler\Persisting\NullablePersister;
use Railt\Compiler\Persisting\Persister;
use Railt\Compiler\Persisting\Psr16Persister;
use Railt\Compiler\Persisting\Psr6Persister;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Filesystem\File;
use Railt\Compiler\Filesystem\ReadableInterface;
use Railt\Tests\AbstractTestCase;

/**
 * Class AbstractReflectionTestCase
 * @package Railt\Tests
 */
abstract class AbstractCompilerTestCase extends AbstractTestCase
{
    /**
     * @param string $body
     * @return array
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\UnexpectedTokenException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
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
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\UnexpectedTokenException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     */
    protected function getDocuments(string $body): iterable
    {
        $readable = File::fromSources($body);

        foreach ($this->getCompilers() as $compiler) {
            yield $compiler->compile($readable);
        }
    }

    /**
     * @return \Generator|Compiler\CompilerInterface[]
     */
    protected function getCompilers(): \Generator
    {
        // Default
        yield new Compiler();

        // Nullable (Return Document "as is")
        yield new Compiler(new NullablePersister());

        // Array (Return Document "as is" and store same files into php array stateless memory)
        yield new Compiler(new ArrayPersister());

        // Emulation of data saving
        yield new Compiler(new EmulatingPersister());

        // PSR-6 + Flysystem Serialization
        yield new Compiler($this->getPsr6FileSystemPersister());

        // PSR-16 + Filesystem Serialization
        yield new Compiler($this->getPsr16FileSystemPersister());
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
        $filesystem = new Filesystem(new Local(__DIR__ . '/.temp/'));
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

    /**
     * @var string
     */
    protected $resourcesPath = '';

    /**
     * @param string $file
     * @return string
     */
    public function resource(string $file): string
    {
        return __DIR__ . '/.resources/' . $this->resourcesPath . $file;
    }

    /**
     * @param string $file
     * @return File
     * @throws \Railt\Compiler\Exceptions\NotReadableException
     */
    public function file(string $file): File
    {
        return File::fromPathname($this->resource($file));
    }
}
