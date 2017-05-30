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
use Serafim\Railgun\Schema\SchemaInterface;

/**
 * Class FieldsSchemaTestCase
 * @package Serafim\Railgun\Tests\Schemas
 */
class FieldsSchemaTestCase extends AbstractDefinitionsTestCase
{
    /**
     * @return SchemaInterface|Fields
     * @throws \InvalidArgumentException
     */
    protected function getSchema(): SchemaInterface
    {
        return (new Registry())->get(Fields::class);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testHasOneMethod(): void
    {
        $creator = $this->getSchema()->hasOne('id');

        Assert::assertEquals('Id', $creator->build()->getName());
        Assert::assertTrue($creator->build()->getTypeDefinition()->isNullable());
        Assert::assertFalse($creator->build()->getTypeDefinition()->isList());
    }

    public function testHasManyMethod(): void
    {
        $creator = $this->getSchema()->hasMany('id');

        Assert::assertEquals('Id', $creator->build()->getName());
        Assert::assertTrue($creator->build()->getTypeDefinition()->isNullable());
        Assert::assertTrue($creator->build()->getTypeDefinition()->isList());
    }
}
