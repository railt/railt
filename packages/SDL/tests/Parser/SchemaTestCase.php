<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests\Parser;

use Phplrt\Contracts\Parser\Exception\ParserRuntimeExceptionInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\SDL\Frontend\Ast\Definition\OperationTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\IntValue;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class SchemaTestCase
 */
class SchemaTestCase extends ParserTestCase
{
    /**
     * @return void
     * @throws ParserRuntimeExceptionInterface
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function testSchemaDescription(): void
    {
        $type = $this->schema();

        $this->assertSame('Type description', \trim($type->description->value));
    }

    /**
     * @return Node|SchemaDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function schema(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            """
            Type description
            """
            schema @schemaDirective(a: 23) {
                query: Query
                mutation: Mutation
                subscription: Subscription
            }
        GraphQL
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testSchemaDirectivesCount(): void
    {
        $type = $this->schema();

        $this->assertCount(1, $type->directives);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testSchemaDirectiveName(): void
    {
        $type = $this->schema();

        $this->assertSame('schemaDirective', $type->directives[0]->name->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testSchemaDirectivesArgumentsCount(): void
    {
        $type = $this->schema();

        $this->assertCount(1, $type->directives[0]->arguments);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testSchemaDirectivesArgumentName(): void
    {
        $type = $this->schema();

        $this->assertSame('a', $type->directives[0]->arguments[0]->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testSchemaDirectivesArgumentValue(): void
    {
        $type = $this->schema();

        $this->assertEquals(new IntValue(23), $type->directives[0]->arguments[0]->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testSchemaFieldsCount(): void
    {
        $type = $this->schema();

        $this->assertCount(3, $type->operationTypes);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testSchemaFieldNames(): void
    {
        $type = $this->schema();

        $haystack = \array_map(
            fn(OperationTypeDefinitionNode $op): string => $op->operation,
            $type->operationTypes
        );

        $this->assertSame(['query', 'mutation', 'subscription'], $haystack);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testSchemaFieldTypes(): void
    {
        $type = $this->schema();

        $haystack = \array_map(
            fn(OperationTypeDefinitionNode $op): string => $op->type->name->value,
            $type->operationTypes
        );

        $this->assertSame(['Query', 'Mutation', 'Subscription'], $haystack);
    }
}
