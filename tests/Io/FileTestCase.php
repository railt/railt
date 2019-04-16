<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

use PHPUnit\Framework\ExpectationFailedException;
use Railt\Component\Io\Exception\NotReadableException;
use Railt\Component\Io\File;
use Railt\Component\Io\Readable;

/**
 * Class FactoryTestCase
 */
class FileTestCase extends TestCase
{
    /**
     * @dataProvider provider
     * @param \Closure $factory
     * @throws ExpectationFailedException
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
     * @throws ExpectationFailedException
     */
    public function testPathname(\Closure $factory): void
    {
        /** @var Readable $readable */
        $readable = $factory();

        $path = $readable->getPathname();

        if (! $readable->exists()) {
            $this->markTestSkipped('Unable to test file with arbitrary name');

            return;
        }

        $this->assertSame($path, $readable->getPathname());
        $this->assertSame($path, (clone $readable)->getPathname());
        $this->assertSame($path, \unserialize(\serialize($readable))->getPathname());
    }

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
     * @throws ExpectationFailedException
     */
    public function testRenderable(\Closure $factory): void
    {
        $this->assertSame((string)$factory(), (string)$factory()->getPathname());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws NotReadableException
     */
    public function testIsFile(): void
    {
        $this->assertTrue(File::fromPathname($this->getPathname())->exists());
        $this->assertTrue(File::fromSources($this->getSources(), $this->getPathname())->exists());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testNotFile(): void
    {
        $this->assertFalse(File::fromSources($this->getSources())->exists());
        $this->assertFalse(File::fromSources($this->getSources(), 'not a path')->exists());
    }
}
