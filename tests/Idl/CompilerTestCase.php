<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests;

use PHPUnit\Framework\Assert;
use Serafim\Railgun\IDL\Compiler;

/**
 * Class CompilerTestCase
 * @package Serafim\Railgun\Tests
 */
class CompilerTestCase extends AbstractTestCase
{
    /**
     * @param string $file
     * @return string
     * @throws \RuntimeException
     * @throws \Yay\Halt
     */
    private function read(string $file): string
    {
        return (new Compiler())->compile(__DIR__ . '/../.resources/' . $file);
    }

    /**
     * @throws \RuntimeException
     * @throws \Yay\Halt
     */
    public function testSimpleTypedef(): void
    {
        Assert::assertEquals($this->read('typedef1.out'), $this->read('typedef1.graphqls'));
    }
}
