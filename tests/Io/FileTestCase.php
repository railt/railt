<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

use Railt\Io\File;

/**
 * Class FactoryTestCase
 */
class FileTestCase extends TestCase
{
    /**
     * @return string
     */
    public function getPathname(): string
    {
        return __FILE__;
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     */
    public function testSources(\Closure $factory): void
    {
        $readable = $factory();

        $this->assertSame($this->getSources(), $readable->getContents());
        $this->assertSame($this->getSources(), (clone $readable)->getContents());
        $this->assertSame($this->getSources(), \unserialize(\serialize($readable))->getContents());
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     */
    public function testPathname(\Closure $factory): void
    {
        $readable = $factory();

        $path = $readable->isFile() ? $this->getPathname() : 'php://input';

        $this->assertSame($path, $readable->getPathname());
        $this->assertSame($path, (clone $readable)->getPathname());
        $this->assertSame($path, \unserialize(\serialize($readable))->getPathname());
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     */
    public function testRenderable(\Closure $factory): void
    {
        $this->assertSame((string)$factory(), (string)$factory()->getPathname());
    }

    /**
     * @return void
     */
    public function testIsFile(): void
    {
        $this->assertTrue(File::fromPathname($this->getPathname())->isFile());
        $this->assertTrue(File::fromSources($this->getSources(), $this->getPathname())->isFile());
    }

    /**
     * @return void
     */
    public function testNotFile(): void
    {
        $this->assertFalse(File::fromSources($this->getSources())->isFile());
        $this->assertFalse(File::fromSources($this->getSources(), 'not a path')->isFile());
    }
}
