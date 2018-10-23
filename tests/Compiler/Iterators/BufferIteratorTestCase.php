<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler\Iterators;

use Railt\Compiler\Iterator\Buffer;
use Railt\Tests\Compiler\AbstractCompilerTestCase;

/**
 * Class BufferIteratorTestCase
 */
class BufferIteratorTestCase extends AbstractCompilerTestCase
{
    /**
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIterable(): void
    {
        foreach ($this->bufferOverGenerator(10) as $i => $item) {
            $this->assertSame($i, $item);
        }

        foreach ($this->bufferOverRewindable(10) as $i => $item) {
            $this->assertSame($i, $item);
        }
    }

    /**
     * @param int $size
     * @param int $max
     * @return Buffer
     */
    private function bufferOverGenerator(int $size, int $max = 100): Buffer
    {
        return new Buffer((function () use ($size) {
            for ($i = 0; $i < $size; ++$i) {
                yield $i;
            }
        })->call($this), $max);
    }

    /**
     * @param int $valuesSize
     * @param int $max
     * @return Buffer
     */
    private function bufferOverRewindable(int $valuesSize, int $max = 100): Buffer
    {
        return new Buffer(new \ArrayIterator(\range(0, $valuesSize)), $max);
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testIterableOverCrowdedBuffer(): void
    {
        foreach ($this->bufferOverGenerator(10, 1) as $i => $item) {
            $this->assertSame($i, $item);
        }

        foreach ($this->bufferOverRewindable(10, 1) as $i => $item) {
            $this->assertSame($i, $item);
        }
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testUnchangedBufferVolume(): void
    {
        $sizes = [1, 10, 100, 2342];

        foreach ($sizes as $size) {
            $iterator = $this->bufferOverGenerator(1000, $size);

            foreach ($iterator as $i => $item) {
                $this->assertSame($i, $item);
                $this->assertSame($size, $iterator->size());
            }

            $iterator2 = $this->bufferOverRewindable(1000, $size);

            foreach ($iterator2 as $i => $item) {
                $this->assertSame($i, $item);
                $this->assertSame($size, $iterator->size());
            }
        }
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testRewindableToAvailableVolume(): void
    {
        $iterator = $this->bufferOverRewindable(100, 1000);

        foreach ($iterator as $item) {
            if ($item > 10) {
                $iterator->rewind();
                $this->assertSame(0, $iterator->current());
                break;
            }
        }

        foreach ($iterator as $item) {
            if ($item > 20) {
                $iterator->rewind();
                $this->assertSame(0, $iterator->current());
                break;
            }
        }

        foreach ($iterator as $item) {
            if ($item > 70) {
                $iterator->rewind();
                $this->assertSame(0, $iterator->current());
                break;
            }
        }

        foreach ($iterator as $item) {
            if ($item > 99) {
                $iterator->rewind();
                $this->assertSame(0, $iterator->current());
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
        for ($i = 0; $i < $max; ++$i) {
            if (\random_int(0, 100) === 0) {
                $sizes[] = \random_int(1, $max - 1);
            }
        }

        foreach ($sizes as $size) {
            $iterator = $this->bufferOverRewindable($max, $size);

            foreach ($iterator as $item) {
                if ($item >= $size) {
                    $iterator->rewind();
                    $this->assertSame($item - $size + 1, $iterator->current());
                    break;
                }
            }
        }
    }
}
