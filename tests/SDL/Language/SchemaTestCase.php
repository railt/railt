<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language;

use Railt\Io\File;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class SchemaTestCase
 */
class SchemaTestCase extends AbstractLanguageTestCase
{
    private const VALID_SCHEMA = <<<'GraphQL'
schema {
    query: MyQuery,
    mutation: MyMutation, 
    subscription: MySubscription 
}

type MyQuery {}

type MyMutation {}

type MySubscription {}
GraphQL;

    /**
     * @return array
     * @throws \LogicException
     */
    public function provider(): array
    {
        $result = [];

        foreach ($this->getCompilers() as $compiler) {
            $result[] = [$compiler];
        }

        return $result;
    }

    /**
     * @dataProvider provider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaHasQuery(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(self::VALID_SCHEMA));
        static::assertInstanceOf(Document::class, $document);

        $schema = $document->getSchema();
        static::assertInstanceOf(SchemaDefinition::class, $schema);

        static::assertNotNull($schema->getQuery());
    }

    /**
     * @dataProvider provider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaQueryName(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(self::VALID_SCHEMA));
        static::assertInstanceOf(Document::class, $document);

        $schema = $document->getSchema();
        static::assertInstanceOf(SchemaDefinition::class, $schema);

        static::assertSame('MyQuery', $schema->getQuery()->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaHasMutation(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(self::VALID_SCHEMA));
        static::assertInstanceOf(Document::class, $document);

        $schema = $document->getSchema();
        static::assertInstanceOf(SchemaDefinition::class, $schema);

        static::assertNotNull($schema->getMutation());
    }

    /**
     * @dataProvider provider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaMutationName(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(self::VALID_SCHEMA));
        static::assertInstanceOf(Document::class, $document);

        $schema = $document->getSchema();
        static::assertInstanceOf(SchemaDefinition::class, $schema);

        static::assertSame('MyMutation', $schema->getMutation()->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaHasSubscription(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(self::VALID_SCHEMA));
        static::assertInstanceOf(Document::class, $document);

        $schema = $document->getSchema();
        static::assertInstanceOf(SchemaDefinition::class, $schema);

        static::assertNotNull($schema->getSubscription());
    }

    /**
     * @dataProvider provider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaSubscriptionName(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(self::VALID_SCHEMA));
        static::assertInstanceOf(Document::class, $document);

        $schema = $document->getSchema();
        static::assertInstanceOf(SchemaDefinition::class, $schema);

        static::assertSame('MySubscription', $schema->getSubscription()->getName());
    }

    /**
     * @dataProvider provider
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaTypeName(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(self::VALID_SCHEMA));
        static::assertInstanceOf(Document::class, $document);

        $schema = $document->getSchema();
        static::assertInstanceOf(SchemaDefinition::class, $schema);

        static::assertSame('Schema', $schema->getTypeName());
    }

    /**
     * @dataProvider provider
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaComment(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(<<<'GraphQL'
            schema {
                """Query docs"""
                query: Query
                
                """Mutation docs"""
                mutation: Mutation
                
                """Subscription docs"""
                subscription: Subscription
            }
            
            type Query {}
            type Mutation {}
            type Subscription {}
GraphQL
        ));

        static::assertInstanceOf(Document::class, $document);
    }

    /**
     * @dataProvider provider
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaWithEmptyQuery(CompilerInterface $compiler): void
    {
        $this->expectException(TypeConflictException::class);

        $compiler->compile(File::fromSources('schema {}'));
    }

    /**
     * @dataProvider provider
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaWithInvalidQueryType(CompilerInterface $compiler): void
    {
        $this->expectException(TypeConflictException::class);

        $compiler->compile(File::fromSources('schema { query: String }'));
    }

    /**
     * @dataProvider provider
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaWithInvalidMutationType(CompilerInterface $compiler): void
    {
        $this->expectException(TypeConflictException::class);

        $compiler->compile(File::fromSources('type A{} schema { query: A, mutation: String }'));
    }

    /**
     * @dataProvider provider
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testSchemaWithInvalidSubscriptionType(CompilerInterface $compiler): void
    {
        $this->expectException(TypeConflictException::class);

        $compiler->compile(File::fromSources('type A{} schema { query: A, subscription: String }'));
    }
}
