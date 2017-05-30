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
        yield 'new name' => 'NewName';
    }

    /**
     * @return void
     */
    public function testMutableName(): void
    {
        $mock = $this->mock();
        $mock->rename('name');

        Assert::assertEquals('name', $mock->getName());
    }

    /**
     * @return void
     */
    public function testDefaultNameFormatting(): void
    {
        foreach ($this->mockDefaultFormattedName() as $source => $expected) {
            $mock = $this->mock()->rename($source);

            Assert::assertEquals($expected, $mock->getName());
        }
    }

    /**
     * @return void
     */
    public function testDisabledNameFormatting(): void
    {
        $mock = $this->mock()
            ->withoutNameFormatting()
            ->rename('new Name');

        Assert::assertEquals('newName', $mock->getName());
    }

    /**
     * @return void
     */
    public function testSnakeNameFormatting(): void
    {
        $mock = $this->mock()
            ->inSnakeCase()
            ->rename('new name');

        Assert::assertEquals('new_name', $mock->getName());
    }

    /**
     * @return void
     */
    public function testCamelCaseNameFormatting(): void
    {
        $mock = $this->mock()
            ->inCamelCase()
            ->rename('new name');

        Assert::assertEquals('NewName', $mock->getName());
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
