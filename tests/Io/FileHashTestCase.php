<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

/**
 * Class FileHashTestCase
 */
class FileHashTestCase extends TestCase
{
    /**
     * @dataProvider provider
     * @param \Closure $factory
     */
    public function testHashSize(\Closure $factory): void
    {
        $readable = $factory();

        $this->assertSame(40, \strlen($readable->getHash()));
        $this->assertSame(40, \mb_strlen($readable->getHash()));
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     */
    public function testHashIsConstant(\Closure $factory): void
    {
        $readable = $factory();

        $this->assertSame($readable->getHash(), $factory()->getHash());
        $this->assertSame($readable->getHash(), (clone $readable)->getHash());
        $this->assertSame($readable->getHash(), \unserialize(\serialize($readable))->getHash());
    }

    /**
     * @return string
     */
    protected function getPathname(): string
    {
        return __FILE__;
    }
}
