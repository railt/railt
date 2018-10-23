<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Parser;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Railt\Parser\Ast\NodeInterface;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @param string $expected
     * @param null|NodeInterface $actual
     * @throws \PHPUnit\Framework\Exception
     */
    protected function assertAst(string $expected, ?NodeInterface $actual): void
    {
        $toArray = function (string $code): array {
            $parts = \explode("\n", \str_replace("\r", '', $code));

            return \array_map('\\trim', $parts);
        };

        $this->assertSame($toArray($expected), $toArray((string)$actual),
            \sprintf("Bad ast in: \n%s", (string)$actual));
    }
}
