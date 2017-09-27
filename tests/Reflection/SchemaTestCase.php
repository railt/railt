<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Railt\Reflection\Contracts\Types;

/**
 * Class SchemaTestCase
 * @package Railt\Reflection
 */
class SchemaTestCase extends AbstractReflectionTestCase
{
    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {
        $schema = 'schema { query:MyQuery, mutation:MyMutation, subscription:MySubscription }' .
            'type MyQuery {}' .
            'type MyMutation {}' .
            'type MySubscription {}';

        $result = [];
        foreach ($this->getDocuments($schema) as $document) {
            $result[] = [$document->getSchema()];
        }
        return $result;
    }

    /**
     * @dataProvider provider
     *
     * @param Types\SchemaType $schema
     * @return void
     */
    public function testSchemaHasQuery(Types\SchemaType $schema): void
    {
        static::assertNotNull($schema->getQuery());
    }

    /**
     * @dataProvider provider
     *
     * @param Types\SchemaType $schema
     * @return void
     */
    public function testSchemaQueryName(Types\SchemaType $schema): void
    {
        static::assertEquals('MyQuery', $schema->getQuery()->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param Types\SchemaType $schema
     * @return void
     */
    public function testSchemaHasMutation(Types\SchemaType $schema): void
    {
        static::assertNotNull($schema->getMutation());
    }

    /**
     * @dataProvider provider
     *
     * @param Types\SchemaType $schema
     * @return void
     */
    public function testSchemaMutationName(Types\SchemaType $schema): void
    {
        static::assertEquals('MyMutation', $schema->getMutation()->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param Types\SchemaType $schema
     * @return void
     */
    public function testSchemaHasSubscription(Types\SchemaType $schema): void
    {
        static::assertNotNull($schema->getSubscription());
    }

    /**
     * @dataProvider provider
     *
     * @param Types\SchemaType $schema
     * @return void
     */
    public function testSchemaSubscriptionName(Types\SchemaType $schema): void
    {
        static::assertEquals('MySubscription', $schema->getSubscription()->getName());
    }

    /**
     * @dataProvider provider
     * @param Types\SchemaType $schema
     * @return void
     */
    public function testSchemaTypeName(Types\SchemaType $schema): void
    {
        static::assertEquals('Schema', $schema->getTypeName());
    }
}
