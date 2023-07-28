<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Tests\Unit;

use PHPUnit\Framework\Attributes\Group;
use Railt\TypeSystem\Definition\ArgumentDefinition;
use Railt\TypeSystem\Definition\DirectiveDefinition;
use Railt\TypeSystem\Definition\EnumValueDefinition;
use Railt\TypeSystem\Definition\FieldDefinition;
use Railt\TypeSystem\Definition\InputFieldDefinition;
use Railt\TypeSystem\Definition\SchemaDefinition;
use Railt\TypeSystem\Definition\Type\EnumType;
use Railt\TypeSystem\Definition\Type\InputObjectType;
use Railt\TypeSystem\Definition\Type\InterfaceType;
use Railt\TypeSystem\Definition\Type\ObjectType;
use Railt\TypeSystem\Definition\Type\ScalarType;
use Railt\TypeSystem\Definition\Type\UnionType;
use Railt\TypeSystem\Execution\Argument;
use Railt\TypeSystem\Execution\Directive;
use Railt\TypeSystem\Execution\InputObject;
use Railt\TypeSystem\ListType;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\Tests\Unit\TestCase;

#[Group('railt/type-system')]
class DefinitionStringRepresentationTest extends TestCase
{
    /**
     * @return non-empty-string
     * @throws \Exception
     */
    private function getRandomName(): string
    {
        return 'name' . \bin2hex(\random_bytes(4));
    }

    public function testNonNullType(): void
    {
        $string = (string)(new NonNullType(
            type: new ScalarType(
                name: $name = $this->getRandomName(),
            ),
        ));

        self::assertSame("scalar<$name>!", $string);
    }

    public function testListType(): void
    {
        $string = (string)(new ListType(
            type: new ScalarType(
                name: $name = $this->getRandomName(),
            ),
        ));

        self::assertSame("[scalar<$name>]", $string);
    }

    public function testMultipleWrappingType(): void
    {
        $string = (string)(new NonNullType(
            new ListType(
                new NonNullType(
                    type: new ScalarType(
                        name: $name = $this->getRandomName(),
                    ),
                )
            )
        ));

        self::assertSame("[scalar<$name>!]!", $string);
    }

    public function testArgumentDefinition(): void
    {
        $string = (string)(new ArgumentDefinition(
            name: $argumentName = $this->getRandomName(),
            type: new ScalarType(
                name: $scalarName = $this->getRandomName(),
            ),
        ));

        self::assertSame("argument<$argumentName: scalar<$scalarName>>", $string);
    }

    public function testDirectiveDefinition(): void
    {
        $string = (string)(new DirectiveDefinition(
            name: $directiveName = $this->getRandomName(),
        ));

        self::assertSame("directive<@$directiveName>", $string);
    }

    public function testEnumValueDefinition(): void
    {
        $string = (string)(new EnumValueDefinition(
            name: $enumValue = $this->getRandomName(),
            value: 0xDEAD_BEEF,
        ));

        self::assertSame("enum-value<$enumValue>", $string);
    }

    public function testFieldDefinition(): void
    {
        $string = (string)(new FieldDefinition(
            name: $fieldName = $this->getRandomName(),
            type: new ScalarType(
                name: $scalarName = $this->getRandomName(),
            ),
        ));

        self::assertSame("field<$fieldName: scalar<$scalarName>>", $string);
    }

    public function testInputFieldDefinition(): void
    {
        $string = (string)(new InputFieldDefinition(
            name: $inputFieldName = $this->getRandomName(),
            type: new ScalarType(
                name: $scalarName = $this->getRandomName(),
            ),
        ));

        self::assertSame("input-field<$inputFieldName: scalar<$scalarName>>", $string);
    }

    public function testSchemaDefinition(): void
    {
        $string = (string)(new SchemaDefinition());

        self::assertSame('schema', $string);
    }

    public function testEnumTypeDefinition(): void
    {
        $string = (string)(new EnumType(
            name: $enumName = $this->getRandomName(),
        ));

        self::assertSame("enum<$enumName>", $string);
    }

    public function testInputObjectTypeDefinition(): void
    {
        $string = (string)(new InputObjectType(
            name: $inputObjectName = $this->getRandomName(),
        ));

        self::assertSame("input<$inputObjectName>", $string);
    }

    public function testInterfaceTypeDefinition(): void
    {
        $string = (string)(new InterfaceType(
            name: $interfaceName = $this->getRandomName(),
        ));

        self::assertSame("interface<$interfaceName>", $string);
    }

    public function testObjectTypeDefinition(): void
    {
        $string = (string)(new ObjectType(
            name: $objectName = $this->getRandomName(),
        ));

        self::assertSame("object<$objectName>", $string);
    }

    public function testScalarTypeDefinition(): void
    {
        $string = (string)(new ScalarType(
            name: $scalarName = $this->getRandomName(),
        ));

        self::assertSame("scalar<$scalarName>", $string);
    }

    public function testUnionTypeDefinition(): void
    {
        $string = (string)(new UnionType(
            name: $unionName = $this->getRandomName(),
        ));

        self::assertSame("union<$unionName>", $string);
    }

    public function testArgumentExecution(): void
    {
        $string = (string)(new Argument(
            definition: new ArgumentDefinition(
                name: $argumentName = $this->getRandomName(),
                type: new ScalarType(
                    name: $scalarName = $this->getRandomName(),
                ),
            ),
            value: 0xDEAD_BEEF,
        ));

        self::assertSame("argument<$argumentName: scalar<$scalarName>>", $string);
    }

    public function testDirectiveExecution(): void
    {
        $string = (string)(new Directive(
            definition: new DirectiveDefinition(
                name: $directiveName = $this->getRandomName(),
            ),
        ));

        self::assertSame("directive<@$directiveName>", $string);
    }

    public function testInputObjectExecution(): void
    {
        $string = (string)(new InputObject(
            definition: new InputObjectType(
                name: $inputObjectName = $this->getRandomName(),
            ),
        ));

        self::assertSame("input<$inputObjectName>", $string);
    }
}
