<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTestCase
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @param \Closure $execution
     * @param string $suffix
     * @return void
     */
    protected function positiveTestWrapper(\Closure $execution, string $suffix): void
    {
        try {
            $execution();
            $this->assertTrue(true);
        } catch (\Throwable $e) {
            throw new \LogicException(
                (string)$e->getMessage() . "\n" .
                'Should be successful:' . "\n" .
                $suffix . "\n" .
                \str_repeat('-', 60) . "\n"
            );
        }
    }

    /**
     * @param \Closure $execution
     * @param string $suffix
     * @param string $error
     * @return void
     */
    protected function negativeTestWrapper(\Closure $execution, string $suffix, string $error = \Throwable::class): void
    {
        try {
            $execution();
            $this->assertFalse(true,
                'Should throw an error:' . "\n" .
                $suffix . "\n" .
                \str_repeat('-', 60) . "\n"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf($error, $e,
                'Error must be an instance of ' . $error . ' but ' . \get_class($e) . ' given:' . "\n" .
                (string)$e . "\n" .
                $suffix . "\n" .
                \str_repeat('-', 60) . "\n"
            );
        }
    }
}
