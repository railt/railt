<?php

declare(strict_types=1);

namespace Railt\SDL\Tests\Unit\Compiler;

use PHPUnit\Framework\Attributes\Group;
use Railt\TypeSystem\Definition\Type\EnumType;

#[Group('railt/sdl')]
final class EnumTypeDefinitionTest extends TestCase
{
    public function testName(): void
    {
        /** @var EnumType $enum */
        $enum = $this->type('Example', <<<'GraphQL'
            enum Example
            GraphQL);

        self::assertSame('Example', $enum->getName());
    }

    public function testEmptyDescription(): void
    {
        /** @var EnumType $enum */
        $enum = $this->type('Example', <<<'GraphQL'
            enum Example
            GraphQL);

        self::assertNull($enum->getDescription());
    }

    public function testInlineDescription(): void
    {
        /** @var EnumType $enum */
        $enum = $this->type('Example', <<<'GraphQL'
            "example description"
            enum Example
            GraphQL);

        self::assertSame('example description', $enum->getDescription());
    }

    public function testMultilineDescription(): void
    {
        /** @var EnumType $enum */
        $enum = $this->type('Example', <<<'GraphQL'
            """
            example multiline description
            """
            enum Example
            GraphQL);

        self::assertSame("example multiline description", $enum->getDescription());
    }

    public function testNoValues(): void
    {
        /** @var EnumType $enum */
        $enum = $this->type('Example', <<<'GraphQL'
            enum Example
            GraphQL);

        self::assertSame(0, $enum->getNumberOfValues());
        self::assertCount(0, $enum->getValues());
    }

    public function testValueName(): void
    {
        /** @var EnumType $enum */
        $enum = $this->type('Example', <<<'GraphQL'
            enum Example {
                EXAMPLE
            }
            GraphQL);

        $value = $enum->getValue('EXAMPLE');
        self::assertNotNull($value);
        self::assertNull($enum->getValue('example'));
        self::assertSame('EXAMPLE', $value->getName());
    }

    public function testValueValue(): void
    {
        /** @var EnumType $enum */
        $enum = $this->type('Example', <<<'GraphQL'
            enum Example {
                EXAMPLE
            }
            GraphQL);

        $value = $enum->getValue('EXAMPLE');
        self::assertNotNull($value);
        self::assertSame('EXAMPLE', $value->getValue());
    }

    public function testValueNoDescription(): void
    {
        /** @var EnumType $enum */
        $enum = $this->type('Example', <<<'GraphQL'
            enum Example {
                EXAMPLE
            }
            GraphQL);

        $value = $enum->getValue('EXAMPLE');
        self::assertNotNull($value);
        self::assertNull($value->getDescription());
    }

    public function testValueInlineDescription(): void
    {
        /** @var EnumType $enum */
        $enum = $this->type('Example', <<<'GraphQL'
            enum Example {
                "description" EXAMPLE
            }
            GraphQL);

        $value = $enum->getValue('EXAMPLE');
        self::assertNotNull($value);
        self::assertSame('description', $value->getDescription());
    }

    public function testValueMultilineDescription(): void
    {
        /** @var EnumType $enum */
        $enum = $this->type('Example', <<<'GraphQL'
            enum Example {
                """
                description
                """
                EXAMPLE
            }
            GraphQL);

        $value = $enum->getValue('EXAMPLE');
        self::assertNotNull($value);
        self::assertSame("description", $value->getDescription());
    }
}
