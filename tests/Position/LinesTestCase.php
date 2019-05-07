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
 * Class LinesTestCase
 */
class LinesTestCase extends TestCase
{
    /**
     * @dataProvider provider
     * @param string $text
     * @param int $lines
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testOffsetOverflow(string $text, int $lines): void
    {
        $position = Position::fromOffset($text, \PHP_INT_MAX);

        $this->assertSame($lines, $position->getLine());
    }

    /**
     * @dataProvider provider
     * @param string $text
     * @param int $lines
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testOffsetUnderflow(string $text, int $lines): void
    {
        $position = Position::fromOffset($text, \PHP_INT_MIN);

        $this->assertSame(1, $position->getLine());
    }

    /**
     * @dataProvider provider
     * @param string $text
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPosition(string $text): void
    {
        $line = 1;

        for ($offset = 0, $length = \strlen($text); $offset < $length; ++$offset) {
            if ($text[$offset] === "\n") {
                ++$line;
            }

            $this->assertSame($line, Position::fromOffset($text, $offset)->getLine());
        }
    }
}
