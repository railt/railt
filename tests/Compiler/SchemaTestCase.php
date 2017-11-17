<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Reflection\Contracts\Definitions\SchemaDefinition;

/**
 * Class SchemaTestCase.
 */
class SchemaTestCase extends AbstractCompilerTestCase
{
    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\UnexpectedTokenException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
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
     * @param SchemaDefinition $schema
     * @return void
     */
    public function testSchemaHasQuery(SchemaDefinition $schema): void
    {
        static::assertNotNull($schema->getQuery());
    }

    /**
     * @dataProvider provider
     *
     * @param SchemaDefinition $schema
     * @return void
     */
    public function testSchemaQueryName(SchemaDefinition $schema): void
    {
        static::assertEquals('MyQuery', $schema->getQuery()->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param SchemaDefinition $schema
     * @return void
     */
    public function testSchemaHasMutation(SchemaDefinition $schema): void
    {
        static::assertNotNull($schema->getMutation());
    }

    /**
     * @dataProvider provider
     *
     * @param SchemaDefinition $schema
     * @return void
     */
    public function testSchemaMutationName(SchemaDefinition $schema): void
    {
        static::assertEquals('MyMutation', $schema->getMutation()->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param SchemaDefinition $schema
     * @return void
     */
    public function testSchemaHasSubscription(SchemaDefinition $schema): void
    {
        static::assertNotNull($schema->getSubscription());
    }

    /**
     * @dataProvider provider
     *
     * @param SchemaDefinition $schema
     * @return void
     */
    public function testSchemaSubscriptionName(SchemaDefinition $schema): void
    {
        static::assertEquals('MySubscription', $schema->getSubscription()->getName());
    }

    /**
     * @dataProvider provider
     * @param SchemaDefinition $schema
     * @return void
     */
    public function testSchemaTypeName(SchemaDefinition $schema): void
    {
        static::assertEquals('Schema', $schema->getTypeName());
    }
}
