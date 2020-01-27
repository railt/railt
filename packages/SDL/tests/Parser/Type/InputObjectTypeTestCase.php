<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests\Parser\Type;

use Phplrt\Contracts\Parser\Exception\ParserRuntimeExceptionInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\SDL\Frontend\Ast\Definition\Type\InputObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\NonNullTypeNode;
use Railt\SDL\Tests\Parser\ParserTestCase;
use Railt\TypeSystem\Value\InputObjectValue;
use Railt\TypeSystem\Value\IntValue;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class InputObjectTypeTestCase
 */
class InputObjectTypeTestCase extends ParserTestCase
{
    /**
     * @return void
     * @throws ParserRuntimeExceptionInterface
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function testInputObjectDescription(): void
    {
        $type = $this->inputObjectType();

        $this->assertSame('Type description', \trim($type->description->value));
    }

    /**
     * @return Node|InputObjectTypeDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function inputObjectType(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            """
            Type description
            """
            input Example @typeDirective(a: 23) {
                "field description"
                field: FieldType! = {b: 42}
                    @fieldDirective(c: 666)
            }
        GraphQL);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testInputObjectName(): void
    {
        $type = $this->inputObjectType();

        $this->assertSame('Example', $type->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testInputObjectDirectivesCount(): void
    {
        $type = $this->inputObjectType();

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
    public function testInputObjectDirectiveName(): void
    {
        $type = $this->inputObjectType();

        $this->assertSame('typeDirective', $type->directives[0]->name->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testInputObjectDirectivesArgumentsCount(): void
    {
        $type = $this->inputObjectType();

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
    public function testInputObjectDirectivesArgumentName(): void
    {
        $type = $this->inputObjectType();

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
    public function testInputObjectDirectivesArgumentValue(): void
    {
        $type = $this->inputObjectType();

        $this->assertEquals(new IntValue(23), $type->directives[0]->arguments[0]->value);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldsCount(): void
    {
        $type = $this->inputObjectType();

        $this->assertCount(1, $type->fields);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldName(): void
    {
        $type = $this->inputObjectType();

        $this->assertSame('field', $type->fields[0]->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldDescription(): void
    {
        $type = $this->inputObjectType();

        $this->assertSame('field description', $type->fields[0]->description->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldDirectivesCount(): void
    {
        $type = $this->inputObjectType();

        $this->assertCount(1, $type->fields[0]->directives);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldDirectiveName(): void
    {
        $type = $this->inputObjectType();

        $this->assertSame('fieldDirective', $type->fields[0]->directives[0]->name->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldDirectiveArgumentsCount(): void
    {
        $type = $this->inputObjectType();

        $this->assertCount(1, $type->fields[0]->directives[0]->arguments);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldType(): void
    {
        $type = $this->inputObjectType();

        /** @var NonNullTypeNode $ofType */
        $ofType = $type->fields[0]->type;

        $this->assertInstanceOf(NonNullTypeNode::class, $ofType);
        $this->assertSame('FieldType', $ofType->type->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldDefaultValue(): void
    {
        $type = $this->inputObjectType();

        $expected = new InputObjectValue([
            'b' => new IntValue(42),
        ]);

        $this->assertEquals($expected, $type->fields[0]->defaultValue);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldDirectiveArgumentName(): void
    {
        $type = $this->inputObjectType();

        $this->assertSame('c', $type->fields[0]->directives[0]->arguments[0]->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldDirectiveArgumentValue(): void
    {
        $type = $this->inputObjectType();

        $this->assertEquals(new IntValue(666), $type->fields[0]->directives[0]->arguments[0]->value);
    }
}
