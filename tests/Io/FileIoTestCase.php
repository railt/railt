<?php
/**
 * This file is part of Io package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

use Railt\Io\File;

/**
 * Class ReadTestCase
 */
class FileIoTestCase extends AbstractIoTestCase
{
    /**
     * @return void
     */
    public function testPathname(): void
    {
        $a = File::fromPathname(__DIR__ . '/.resources/a.txt');
        $this->assertSame('a.txt', \basename($a->getPathname()));
    }

    /**
     * @return void
     */
    public function testContents(): void
    {
        $a = File::fromPathname(__DIR__ . '/.resources/a.txt');
        $this->assertSame('example', \trim($a->getContents()));
    }

    /**
     * @return void
     */
    public function testIsPhysicalFile(): void
    {
        $b = File::fromPathname(__DIR__ . '/.resources/b.txt');

        $this->assertTrue($b->isFile());
    }

    /**
     * @return void
     */
    public function testIsVirtualFile(): void
    {
        $b = File::fromSources('example');

        $this->assertFalse($b->isFile());
    }

    /**
     * @return void
     */
    public function testVirtualFileHash(): void
    {
        $a = File::fromSources('');
        $b = File::fromSources('');

        $this->assertSame($a->getHash(), $b->getHash());
    }

    /**
     * @return void
     */
    public function testPhysicalFileHash(): void
    {
        $a = File::fromPathname(__DIR__ . '/.resources/b.txt');
        $b = File::fromPathname(__DIR__ . '/.resources/b.txt');

        $this->assertSame($a->getHash(), $b->getHash());
    }
}
