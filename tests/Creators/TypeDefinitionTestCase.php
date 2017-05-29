<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Creators;

use PHPUnit\Framework\Assert;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Schema\Creators\TypeDefinitionCreator;

/**
 * Class TypeDefinitionTestCase
 * @package Serafim\Railgun\Tests\Creators
 */
class TypeDefinitionTestCase extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testTypeName(): void
    {
        $creator = new TypeDefinitionCreator('test');

        Assert::assertEquals('test', $creator->build()->getTypeName());
    }

    /**
     * @return void
     */
    public function testListDefaultValue(): void
    {
        $creator = new TypeDefinitionCreator('test');

        Assert::assertFalse($creator->build()->isList());
    }

    /**
     * @return void
     */
    public function testListMutableValue(): void
    {
        $creator = new TypeDefinitionCreator('test');

        $creator->many();
        Assert::assertTrue($creator->build()->isList());

        $creator->single();
        Assert::assertFalse($creator->build()->isList());
    }

    /**
     * @return void
     */
    public function testNullableDefaultValue(): void
    {
        $creator = new TypeDefinitionCreator('test');

        Assert::assertTrue($creator->build()->isNullable());
    }

    /**
     * @return void
     */
    public function testNullableMutableValue(): void
    {
        $creator = new TypeDefinitionCreator('test');

        $creator->required();
        Assert::assertFalse($creator->build()->isNullable());

        $creator->nullable();
        Assert::assertTrue($creator->build()->isNullable());
    }
}
