<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL;

use Cache\Adapter\Common\AbstractCachePool;
use Cache\Adapter\Common\CacheItem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use Illuminate\Support\Str;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\ListFiles;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Io\Writable;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Compiler;
use Railt\SDL\Reflection\CompilerInterface;
use Railt\Storage\ArrayPersister;
use Railt\Storage\EmulatingPersister;
use Railt\Storage\NullablePersister;
use Railt\Storage\Persister;
use Railt\Storage\Psr16Persister;
use Railt\Storage\Psr6Persister;
use Railt\Tests\AbstractTestCase;
use Symfony\Component\Finder\Finder;

/**
 * Class AbstractReflectionTestCase
 */
abstract class AbstractCompilerTestCase extends AbstractTestCase
{
    /**
     * @var string
     */
    protected $resourcesPath = '';

    /**
     * @var string
     */
    protected $specDirectory = __DIR__ . '/.resources';

    /**
     * @var bool
     */
    private static $booted = false;

    /**
     * @return void
     */
    public function setUp(): void
    {
        if (self::$booted === false) {
            self::$booted = true;

            $filesystem = new Filesystem(new Local(__DIR__ . '/.temp/'));
            $filesystem->addPlugin(new ListFiles());

            foreach ($filesystem->listFiles('/', true) as $file) {
                if (Str::startsWith($file['basename'], '.')) {
                    continue;
                }
                // Clear cache
                $filesystem->delete($file['path']);
            }
        }

        parent::setUp();
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\SkippedTestError
     */
    public function testProviderIsLoadable(): void
    {
        if (! \method_exists($this, 'provider')) {
            static::markTestSkipped(__CLASS__ . ' does not provide a data provider');
            return;
        }

        static::assertInternalType('array', $this->provider());
        foreach ($this->provider() ?? [] as $item) {
            static::assertInternalType('array', $item);
        }
    }

    /**
     * @param string $body
     * @return array|Document[][]
     */
    public function dataProviderDocuments(string $body): array
    {
        $result = [];

        foreach ($this->getDocuments($body) as $document) {
            $result[] = [$document];
        }

        return $result;
    }

    /**
     * @return array|CompilerInterface[]
     * @throws \LogicException
     */
    public function dateCompilersProvider(): array
    {
        $result = [];

        foreach ($this->getCompilers() as $compiler) {
            $result[] = [$compiler];
        }

        return $result;
    }

    /**
     * @param string $body
     * @return iterable|Document[]
     */
    protected function getDocuments(string $body): iterable
    {
        $readable = File::fromSources($body);

        foreach ($this->getCompilers() as $compiler) {
            yield $compiler->compile($readable);
        }
    }

    /**
     * @return \Generator|Compiler[]
     */
    protected function getCompilers(): \Generator
    {
        // Default
        yield new Compiler(null);

        // Nullable (Return Document "as is")
        yield new Compiler(new NullablePersister());

        // Array (Return Document "as is" and store same files into php array stateless memory)
        yield new Compiler(new ArrayPersister());

        // Emulation of data saving
        yield new Compiler(new EmulatingPersister());

        // PSR-6 + Flysystem Serialization
        yield new Compiler($this->getPsr6FileSystemPersister());

        // PSR-16 + system Serialization
        yield new Compiler($this->getPsr16FileSystemPersister());
    }

    /**
     * @return Persister
     */
    private function getPsr6FileSystemPersister(): Persister
    {
        $cachePool = $this->createFilesystemPool('psr6');

        return new Psr6Persister($cachePool, function (Readable $readable, Document $document) {
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
        $folder     = \snake_case(\class_basename($this)) .
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
     * @param string $file
     * @return string
     */
    public function resource(string $file): string
    {
        return __DIR__ . '/.resources/' . $this->resourcesPath . $file;
    }

    /**
     * @return array
     */
    public function loadPositiveABTests(): array
    {
        $finder = (new Finder())
            ->files()
            ->in($this->specDirectory)
            ->name('+*.graphqls');

        return $this->formatProvider($finder->getIterator());
    }

    /**
     * @return array
     */
    public function loadNegativeABTests(): array
    {
        $finder = (new Finder())
            ->files()
            ->in($this->specDirectory)
            ->name('-*.graphqls');

        return $this->formatProvider($finder->getIterator());
    }

    /**
     * @param \Traversable $files
     * @return array
     */
    private function formatProvider(\Traversable $files): array
    {
        $tests = [];

        foreach ($files as $test) {
            $tests[] = [File::fromSplFileInfo($test)];
        }

        return $tests;
    }

    /**
     * @param string $file
     * @return File|Writable|Readable
     */
    public function file(string $file): File
    {
        return File::fromPathname($this->resource($file));
    }
}
