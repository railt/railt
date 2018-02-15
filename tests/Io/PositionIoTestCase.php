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
 * Class PositionTestCase
 */
class PositionIoTestCase extends AbstractIoTestCase
{
    /**
     * @return void
     */
    public function testPosition(): void
    {
        $b = File::fromPathname(__DIR__ . '/.resources/b.txt');

        for ($i = 0, $len = \strlen($b->getContents()); $i < $len; ++$i) {
            $lines = \explode("\n", \substr($b->getContents(), 0, $i));

            $position = $b->getPosition($i);

            $this->assertEquals($i, $position->getOffset(), 'Invalid offset');
            $this->assertEquals(\count($lines), $position->getLine(), 'Invalid line');
            $this->assertEquals(\strlen($lines[\count($lines) - 1]), $position->getColumn(), 'Invalid column');
        }
    }

    /**
     * @return void
     */
    public function testPositionOverflow(): void
    {
        $b = File::fromPathname(__DIR__ . '/.resources/b.txt');

        $lines = \substr_count($b->getContents(), "\n");

        $position = $b->getPosition(\PHP_INT_MAX);

        $this->assertEquals(\strlen($b->getContents()), $position->getOffset(), 'Invalid offset');
        $this->assertEquals($lines, $position->getLine(), 'Invalid line');
        $this->assertEquals(0, $position->getColumn(), 'Invalid column');
    }
}
