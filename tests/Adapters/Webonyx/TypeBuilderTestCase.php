<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Adapters\Webonyx;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\UnionType;
use PHPUnit\Framework\Assert;
use Serafim\Railgun\Endpoint;
use GraphQL\Type\Definition\Type;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Tests\Mocks\MockEnumType;
use Serafim\Railgun\Tests\Mocks\MockInterfaceType;
use Serafim\Railgun\Tests\Mocks\MockObjectType;
use Serafim\Railgun\Adapters\Webonyx\Builders\TypeBuilder;
use Serafim\Railgun\Tests\Mocks\MockUnionType;

/**
 * Class TypeBuilderTestCase
 * @package Serafim\Railgun\Tests\Adapters\Webonyx
 */
class TypeBuilderTestCase extends AbstractTestCase
{
    /**
     * @param string $type
     * @param null|string $context
     * @return Type
     */
    private function build(string $type, ?string $context = null): Type
    {
        $builder = new TypeBuilder(new Endpoint('test'));

        return $builder->build($builder->type($type), $context);
    }

    /**
     * @return void
     */
    public function testObjectTypeFactoryResolving(): void
    {
        $type = $this->build(MockObjectType::class);

        Assert::assertInstanceOf(ObjectType::class, $type);
    }

    /**
     * @return void
     */
    public function testInterfaceTypeFactoryResolving(): void
    {
        $type = $this->build(MockInterfaceType::class);

        Assert::assertInstanceOf(InterfaceType::class, $type);
    }

    /**
     * @return void
     */
    public function testEnumTypeFactoryResolving(): void
    {
        $type = $this->build(MockEnumType::class);

        Assert::assertInstanceOf(EnumType::class, $type);
    }

    /**
     * @return void
     */
    public function testUnionTypeFactoryResolving(): void
    {
        $type = $this->build(MockUnionType::class);

        Assert::assertInstanceOf(UnionType::class, $type);
    }

    /**
     * @return void
     */
    public function testBuilderIdentityMap(): void
    {
        $builder = new TypeBuilder(new Endpoint('test'));

        $internal = $builder->type(MockObjectType::class);

        // This line of code should separate the object's Zval memory
        $internal->test = random_int(PHP_INT_MIN, PHP_INT_MAX);

        // Original types has same memory address
        Assert::assertEquals($internal, $builder->type(MockObjectType::class));
        Assert::assertEquals($internal->test, $builder->type(MockObjectType::class)->test);

        $webonyx = $builder->build($internal);

        // Same: This line of code should separate the object's Zval memory
        $webonyx->test = random_int(PHP_INT_MIN, PHP_INT_MAX);

        // Webonyx types has same memory address
        Assert::assertEquals($webonyx, $builder->build($internal));
        Assert::assertEquals($webonyx->test, $builder->build($internal)->test);
    }
}
