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
     * @return Types\SchemaType
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    protected function getSchema(): Types\SchemaType
    {
        return $this->getDocument(
            'schema { query:MyQuery, mutation:MyMutation, subscription:MySubscription }' .
            'type MyQuery {}' .
            'type MyMutation {}' .
            'type MySubscription {}'
        )->getSchema();
    }

    /**
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testSchemaHasQuery()
    {
        $schema = $this->getSchema();

        static::assertNotNull($schema->getQuery());
    }

    /**
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testSchemaQueryName()
    {
        $schema = $this->getSchema();

        static::assertEquals('MyQuery', $schema->getQuery()->getName());
    }

    /**
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testSchemaHasMutation()
    {
        $schema = $this->getSchema();

        static::assertNotNull($schema->getMutation());
    }

    /**
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testSchemaMutationName()
    {
        $schema = $this->getSchema();

        static::assertEquals('MyMutation', $schema->getMutation()->getName());
    }

    /**
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testSchemaHasSubscription()
    {
        $schema = $this->getSchema();

        static::assertNotNull($schema->getSubscription());
    }

    /**
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testSchemaSubscriptionName()
    {
        $schema = $this->getSchema();

        static::assertEquals('MySubscription', $schema->getSubscription()->getName());
    }

    /**
     * @return void
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testSchemaTypeName(): void
    {
        $schema = $this->getSchema();

        static::assertEquals('Schema', $schema->getTypeName());
    }
}
