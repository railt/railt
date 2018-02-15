<?php
/**
 * This file is part of Io package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

use PHPUnit\Framework\TestCase;
use Railt\Io\File;

/**
 * Class ConstructorsTestCase
 */
class ConstructorsTestCase extends TestCase
{
    /**
     * @return void
     */
    public function testFromAnotherReadable(): void
    {
        $a = File::fromPathname(__DIR__ . '/.resources/b.txt');
        $b = File::fromReadable($a);

        $this->assertEquals($a->getContents(), $b->getContents());
        $this->assertEquals($a->getHash(), $b->getHash());
    }

    /**
     * @return void
     */
    public function testFromSplFile(): void
    {
        $a = File::fromPathname(__DIR__ . '/.resources/b.txt');
        $b = File::fromSplFileInfo(new \SplFileInfo(__DIR__ . '/.resources/b.txt'));

        $this->assertEquals($a->getContents(), $b->getContents());
    }
}
