<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Position;

use Railt\Component\Position\Position;

/**
 * Class ColumnsTestCase
 */
class ColumnsTestCase extends TestCase
{
    /**
     * @dataProvider provider
     * @param string $text
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testOffsetOverflow(string $text): void
    {
        $position = Position::fromOffset($text, \PHP_INT_MAX);

        $this->assertSame(1, $position->getColumn());
    }

    /**
     * @dataProvider provider
     * @param string $text
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testOffsetUnderflow(string $text): void
    {
        $position = Position::fromOffset($text, \PHP_INT_MIN);

        $this->assertSame(1, $position->getColumn());
    }

    /**
     * @dataProvider provider
     * @param string $text
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPosition(string $text): void
    {
        $column = 0;

        for ($offset = 0, $length = \strlen($text); $offset < $length; ++$offset) {
            if ($text[$offset] === "\n") {
                $column = 1;
            } else {
                ++$column;
            }

            $this->assertSame($column, Position::fromOffset($text, $offset)->getColumn());
        }
    }
}
