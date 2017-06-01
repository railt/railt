<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Support;

use PHPUnit\Framework\Assert;
use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Support\NameableInterface;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Tests\Mocks\MockContainsName;

/**
 * Class NameableTestCase
 * @package Serafim\Railgun\Tests\Support
 */
class NameableTestCase extends AbstractTestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testNameAutoBuildable(): void
    {
        Assert::assertEquals('MockContainsName',
            (new MockContainsName())->getName());
    }

    /**
     * @throws \ReflectionException
     */
    public function testDescriptionAutoBuildable(): void
    {
        Assert::assertEquals('Mock contains name custom definition',
            (new MockContainsName)->getDescription());
    }


    /**
     * @throws \ReflectionException
     */
    public function testNameWithoutFormatting(): void
    {
        $mock = new MockContainsName();

        Assert::assertEquals('MockContainsName', $mock->getName());
    }

    /**
     * @throws \ReflectionException
     */
    public function testNameResolvableFromAnonymousClassInherits(): void
    {
        $mock = new class extends MockContainsName {};

        Assert::assertEquals('MockContainsName', $mock->getName());
    }

    /**
     * @return void
     */
    public function testNameResolvableFromAnonymousClass(): void
    {
        $mock = new class implements NameableInterface {
            use InteractWithName;
        };

        Assert::assertEquals('AnonymousClass', $mock->getName());
    }

    /**
     * @throws \ReflectionException
     */
    public function testDescriptionSuffixOverwritten(): void
    {
        $mock = new class extends MockContainsName {
            protected function getDescriptionSuffix(): string
            {
                return 'test';
            }
        };

        Assert::assertEquals('Mock contains name test',
            $mock->getDescription());
    }
}

