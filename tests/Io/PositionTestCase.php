<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

use Railt\Io\PositionInterface;
use Railt\Io\Readable;

/**
 * Class PositionTestCase
 */
class PositionTestCase extends TestCase
{
    /**
     * @return string
     */
    protected function getPathname(): string
    {
        return __FILE__;
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     * @throws \Exception
     */
    public function testPosition(\Closure $factory): void
    {
        /** @var Readable $readable */
        $readable = $factory();

        $size = \strlen($this->getSources());

        for ($offset = 0; $offset < $size; $offset += $size / \random_int(20, 1000)) {
            $offset = (int)\round($offset);
            $chunk = \substr($this->getSources(), 0, $offset);

            /** @var PositionInterface $position */
            $position = $readable->getPosition($offset);

            $this->assertSame(\substr_count($chunk, "\n") + 1, $position->getLine());
            $this->assertGreaterThan(0, $position->getColumn());

            if (\method_exists($position, 'getOffset')) {
                $this->assertSame($offset, $position->getOffset());
            }
        }
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     * @throws \Exception
     */
    public function testPositionOverflow(\Closure $factory): void
    {
        /** @var Readable $readable */
        $readable = $factory();

        $position = $readable->getPosition(\PHP_INT_MAX);

        // Max line of the file
        $this->assertSame(\substr_count($this->getSources(), "\n") + 1, $position->getLine());

        // Max offset of the file
        $this->assertSame(1, $position->getColumn());
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     * @throws \Exception
     */
    public function testPositionUnderflow(\Closure $factory): void
    {
        /** @var Readable $readable */
        $readable = $factory();

        $position = $readable->getPosition(\PHP_INT_MIN);

        $this->assertSame(1, $position->getLine());
        $this->assertSame(1, $position->getColumn());
    }
}
