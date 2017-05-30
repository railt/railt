<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Schemas;

use PHPUnit\Framework\Assert;
use Serafim\Railgun\Schema\Arguments;
use Serafim\Railgun\Schema\Fields;
use Serafim\Railgun\Schema\Registry;
use Serafim\Railgun\Schema\BelongsTo;
use Serafim\Railgun\Tests\AbstractTestCase;
use PHPUnit\Framework\AssertionFailedError;

/**
 * Class RegistryTestCase
 * @package Serafim\Railgun\Tests\Schemas
 */
class RegistryTestCase extends AbstractTestCase
{
    /**
     * @throws AssertionFailedError
     * @throws \InvalidArgumentException
     */
    public function testTypesCreatingEvents(): void
    {
        $callbackWasCalled = false;

        $registry = new Registry(function(string $schema) use (&$callbackWasCalled) {
            $callbackWasCalled = $schema;
            return new $schema;
        });

        $registry->get(BelongsTo::class);

        Assert::assertNotFalse($callbackWasCalled);
        Assert::assertEquals(BelongsTo::class, $callbackWasCalled);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypesCreatingEventReturnedType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $registry = new Registry(function(string $name) {
            return null;
        });

        $registry->get(BelongsTo::class);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testBelongsToSchemaIsCreatable(): void
    {
        $registry = new Registry();
        Assert::assertInstanceOf(BelongsTo::class, $registry->get(BelongsTo::class));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testArgumentsSchemaIsCreatable(): void
    {
        $registry = new Registry();
        Assert::assertInstanceOf(Arguments::class, $registry->get(Arguments::class));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testFieldsSchemaIsCreatable(): void
    {
        $registry = new Registry();
        Assert::assertInstanceOf(Fields::class, $registry->get(Fields::class));
    }
}
