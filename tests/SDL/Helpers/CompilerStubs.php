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
use Cache\Adapter\Filesystem\FilesystemCachePool;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Illuminate\Support\Str;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Railt\SDL\Compiler;
use Railt\SDL\Schema\CompilerInterface;

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
        $storage = __DIR__ . '/.temp/';

        // Default
        yield new Compiler();

        // Array (Return Document "as is" and store same files into php array stateless memory)
        yield new Compiler(new ArrayCachePool());

        // PSR-16 + system Serialization
        yield new Compiler($this->createFilesystemPool('psr16', $storage));
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

        $folder = Str::snake(\class_basename($this)) .
            '/' . $name . '_' . \random_int(0, \PHP_INT_MAX);

        return new FilesystemCachePool($filesystem, $folder);
    }
}
