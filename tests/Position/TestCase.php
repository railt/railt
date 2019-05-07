<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Position;

use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provider(): array
    {
        $result = [];

        for ($i = 1; $i < 10; ++$i) {
            $string = '';

            for ($j = 1, $len = \random_int(2, 10); $j < $len; ++$j) {
                $line = \base64_encode(\random_bytes(\random_int(1, $j)));
                $string .= \str_replace(['=', '+', '/'], '', $line) . "\n";
            }

            $result[] = [$string, $j];
        }

        return $result;
    }
}
