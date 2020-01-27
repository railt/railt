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
use Railt\SDL\Frontend\Ast\Definition\ArgumentDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;
use Railt\SDL\Tests\Parser\ParserTestCase;
use Railt\TypeSystem\Value\IntValue;
use Railt\TypeSystem\Value\StringValue;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class ObjectTypeTestCase
 */
class ObjectTypeTestCase extends ParserTestCase
{
    /**
     * @return void
     * @throws ParserRuntimeExceptionInterface
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function testObjectDescription(): void
    {
        $type = $this->objectType();

        $this->assertSame('Type description', \trim($type->description->value));
    }

    /**
     * @return Node|ObjectTypeDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function objectType(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            """
            Type description
            """
            type Example implements Interface1 & Interface2 @typeDirective(a: 23) {
                "field description"
                field(
                    "argument description" 
                    argument: ArgumentType = "argument default"
                        @argumentDirective(b: 42)
                ): FieldType
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
    public function testObjectName(): void
    {
        $type = $this->objectType();

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
    public function testObjectDirectivesCount(): void
    {
        $type = $this->objectType();

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
    public function testObjectDirectiveName(): void
    {
        $type = $this->objectType();

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
    public function testObjectDirectivesArgumentsCount(): void
    {
        $type = $this->objectType();

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
    public function testObjectDirectivesArgumentName(): void
    {
        $type = $this->objectType();

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
    public function testObjectDirectivesArgumentValue(): void
    {
        $type = $this->objectType();

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
    public function testObjectImplementsInterfacesCount(): void
    {
        $type = $this->objectType();

        $this->assertCount(2, $type->interfaces);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testObjectImplementsInterfaceNames(): void
    {
        $type = $this->objectType();

        $this->assertSame('Interface1', $type->interfaces[0]->interface->name->value);
        $this->assertSame('Interface2', $type->interfaces[1]->interface->name->value);
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
        $type = $this->objectType();

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
        $type = $this->objectType();

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
        $type = $this->objectType();

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
        $type = $this->objectType();

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
        $type = $this->objectType();

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
        $type = $this->objectType();

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
        $type = $this->objectType();

        $ofType = $type->fields[0]->type;

        $this->assertInstanceOf(NamedTypeNode::class, $ofType);
        $this->assertSame('FieldType', $ofType->name->value);
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
        $type = $this->objectType();

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
        $type = $this->objectType();

        $this->assertEquals(new IntValue(666), $type->fields[0]->directives[0]->arguments[0]->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldArgumentsCount(): void
    {
        $type = $this->objectType();

        $this->assertCount(1, $type->fields[0]->arguments);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldArgumentName(): void
    {
        $type = $this->objectType();

        /** @var ArgumentDefinitionNode $argument */
        $argument = $type->fields[0]->arguments[0];

        $this->assertSame('argument', $argument->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldArgumentType(): void
    {
        $type = $this->objectType();

        /** @var ArgumentDefinitionNode $argument */
        $argument = $type->fields[0]->arguments[0];

        $this->assertInstanceOf(NamedTypeNode::class, $argument->type);
        $this->assertSame('ArgumentType', $argument->type->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldArgumentDefaultValue(): void
    {
        $type = $this->objectType();

        /** @var ArgumentDefinitionNode $argument */
        $argument = $type->fields[0]->arguments[0];

        $this->assertEquals(new StringValue('argument default'), $argument->defaultValue);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldArgumentDescription(): void
    {
        $type = $this->objectType();

        /** @var ArgumentDefinitionNode $argument */
        $argument = $type->fields[0]->arguments[0];

        $this->assertEquals('argument description', $argument->description->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldArgumentDirectivesCount(): void
    {
        $type = $this->objectType();

        /** @var ArgumentDefinitionNode $argument */
        $argument = $type->fields[0]->arguments[0];

        $this->assertCount(1, $argument->directives);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldArgumentDirectiveName(): void
    {
        $type = $this->objectType();

        /** @var ArgumentDefinitionNode $argument */
        $argument = $type->fields[0]->arguments[0];

        $this->assertSame('argumentDirective', $argument->directives[0]->name->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldArgumentDirectiveArgumentsCount(): void
    {
        $type = $this->objectType();

        /** @var ArgumentDefinitionNode $argument */
        $argument = $type->fields[0]->arguments[0];

        $this->assertCount(1, $argument->directives[0]->arguments);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldArgumentDirectiveArgumentName(): void
    {
        $type = $this->objectType();

        /** @var ArgumentDefinitionNode $argument */
        $argument = $type->fields[0]->arguments[0];

        $this->assertSame('b', $argument->directives[0]->arguments[0]->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testFieldArgumentDirectiveArgumentValue(): void
    {
        $type = $this->objectType();

        /** @var ArgumentDefinitionNode $argument */
        $argument = $type->fields[0]->arguments[0];

        $this->assertEquals(new IntValue(42), $argument->directives[0]->arguments[0]->value);
    }
}
