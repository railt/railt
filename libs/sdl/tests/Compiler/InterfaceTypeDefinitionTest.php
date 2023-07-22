<?php

declare(strict_types=1);

namespace Railt\SDL\Tests\Compiler;

use PHPUnit\Framework\Attributes\Group;
use Railt\TypeSystem\Definition\Type\InterfaceTypeDefinition;

#[Group('railt/sdl')]
final class InterfaceTypeDefinitionTest extends TestCase
{
    public function testName(): void
    {
        /** @var InterfaceTypeDefinition $interface */
        $interface = $this->type('Example', <<<'GraphQL'
            interface Example
            GraphQL);

        self::assertSame('Example', $interface->getName());
    }

    public function testEmptyDescription(): void
    {
        /** @var InterfaceTypeDefinition $interface */
        $interface = $this->type('Example', <<<'GraphQL'
            interface Example
            GraphQL);

        self::assertNull($interface->getDescription());
    }

    public function testInlineDescription(): void
    {
        /** @var InterfaceTypeDefinition $interface */
        $interface = $this->type('Example', <<<'GraphQL'
            "example description"
            interface Example
            GraphQL);

        self::assertSame('example description', $interface->getDescription());
    }

    public function testMultilineDescription(): void
    {
        /** @var InterfaceTypeDefinition $interface */
        $interface = $this->type('Example', <<<'GraphQL'
            """
            example multiline description
            """
            interface Example
            GraphQL);

        self::assertSame("\nexample multiline description\n", $interface->getDescription());
    }

    public function testNoFields(): void
    {
        /** @var InterfaceTypeDefinition $interface */
        $interface = $this->type('Example', <<<'GraphQL'
            interface Example
            GraphQL);

        self::assertSame(0, $interface->getNumberOfFields());
        self::assertCount(0, $interface->getFields());
    }

    public function testFieldName(): void
    {
        /** @var InterfaceTypeDefinition $interface */
        $interface = $this->type('Example', <<<'GraphQL'
            interface Example {
                EXAMPLE: String
            }
            GraphQL);

        $field = $interface->getField('EXAMPLE');

        self::assertNotNull($field);
        self::assertNull($interface->getField('example'));
        self::assertSame('EXAMPLE', $field->getName());
    }
}
