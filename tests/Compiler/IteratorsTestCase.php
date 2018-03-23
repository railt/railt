<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Compiler\Iterator\BufferedIterator;

/**
 * Class IteratorsTestCase
 */
class IteratorsTestCase extends AbstractCompilerTestCase
{

    /**
     * @return void
     */
    public function testIterable(): void
    {
        foreach ($this->bufferOverGenerator(10) as $i => $item) {
            $this->assertEquals($i, $item);
        }

        foreach ($this->bufferOverRewindable(10) as $i => $item) {
            $this->assertEquals($i, $item);
        }
    }

    /**
     * @param int $size
     * @param int $max
     * @return BufferedIterator
     */
    private function bufferOverGenerator(int $size, int $max = 100): BufferedIterator
    {
        return new BufferedIterator((function () use ($size) {
            for ($i = 0; $i < $size; ++$i) {
                yield $i;
            }
        })->call($this), $max);
    }

    /**
     * @param int $valuesSize
     * @param int $max
     * @return BufferedIterator
     */
    private function bufferOverRewindable(int $valuesSize, int $max = 100): BufferedIterator
    {
        return new BufferedIterator(new \ArrayIterator(\range(0, $valuesSize)), $max);
    }

    /**
     * @return void
     */
    public function testIterableOverCrowdedBuffer(): void
    {
        foreach ($this->bufferOverGenerator(10, 1) as $i => $item) {
            $this->assertEquals($i, $item);
        }

        foreach ($this->bufferOverRewindable(10, 1) as $i => $item) {
            $this->assertEquals($i, $item);
        }
    }

    /**
     * @return void
     */
    public function testUnchangedBufferVolume(): void
    {
        $sizes = [1, 10, 100, 2342];

        foreach ($sizes as $size) {
            $iterator = $this->bufferOverGenerator(1000, $size);

            foreach ($iterator as $i => $item) {
                $this->assertEquals($i, $item);
                $this->assertEquals($size, $iterator->getBufferSize());
            }

            $iterator2 = $this->bufferOverRewindable(1000, $size);

            foreach ($iterator2 as $i => $item) {
                $this->assertEquals($i, $item);
                $this->assertEquals($size, $iterator->getBufferSize());
            }
        }
    }

    /**
     * @return void
     */
    public function testRewindableToAvailableVolume(): void
    {
        $iterator = $this->bufferOverRewindable(100, 1000);

        foreach ($iterator as $item) {
            if ($item > 10) {
                $iterator->rewind();
                $this->assertEquals(0, $iterator->current());
                break;
            }
        }

        foreach ($iterator as $item) {
            if ($item > 20) {
                $iterator->rewind();
                $this->assertEquals(0, $iterator->current());
                break;
            }
        }

        foreach ($iterator as $item) {
            if ($item > 70) {
                $iterator->rewind();
                $this->assertEquals(0, $iterator->current());
                break;
            }
        }

        foreach ($iterator as $item) {
            if ($item > 99) {
                $iterator->rewind();
                $this->assertEquals(0, $iterator->current());
                break;
            }
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testRewindableToNonAvailableVolume(): void
    {
        $max   = 1000;
        $sizes = [];
        for ($i = 0; $i < $max; $i++) {
            if (\random_int(0, 100) === 0) {
                $sizes[] = \random_int(1, $max - 1);
            }
        }

        foreach ($sizes as $size) {
            $iterator = $this->bufferOverRewindable($max, $size);

            foreach ($iterator as $item) {
                if ($item >= $size) {
                    $iterator->rewind();
                    $this->assertEquals($item - $size + 1, $iterator->current());
                    break;
                }
            }
        }
    }

    /**
     * @return void
     */
    public function testRewindOverGenerators(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot rewind a generator that was already run');

        $iterator = $this->bufferOverGenerator(100, 1000);
        $iterator->next();
        $iterator->rewind();
    }
}
