<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Concerns;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Support\NameableInterface;

/**
 * Trait ContainsName
 * @package Serafim\Railgun\Tests\Concerns
 * @method InteractWithName|NameableInterface mock()
 */
trait ContainsName
{
    /**
     * @return \Traversable
     */
    protected function mockDefaultFormattedName(): \Traversable
    {
        yield 'new name' => 'newname';
    }

    /**
     * @return void
     */
    public function testMutableName(): void
    {
        $mock = $this->mock();
        (function() {
            $this->rename('name');
        })->call($mock);

        Assert::assertEquals('name', $mock->getName());
    }

    /**
     * @return void
     */
    public function testDefaultNameFormatting(): void
    {
        foreach ($this->mockDefaultFormattedName() as $source => $expected) {
            $mock = $this->mock();

            (function(string $source) {
                $this->rename($source);
            })->call($mock, $source);

            Assert::assertEquals($expected, $mock->getName());
        }
    }

    /**
     * @return void
     */
    public function testDisabledNameFormatting(): void
    {
        $mock = $this->mock();

        (function() {
            $this->rename('new Name');
        })->call($mock);

        Assert::assertEquals('newName', $mock->getName());
    }

    /**
     * @throws AssertionFailedError
     */
    public function testHasDescription(): void
    {
        $mock = $this->mock();

        Assert::assertNotEmpty($mock->getDescription());
    }
}
