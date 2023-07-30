<?php

declare(strict_types=1);

namespace Railt\SDL\Tests\Unit\Compiler;

use PHPUnit\Framework\Attributes\Group;
use Railt\TypeSystem\Definition\Type\ObjectType;

#[Group('railt/sdl')]
final class ObjectTypeDefinitionTest extends TestCase
{
    public function testName(): void
    {
        /** @var ObjectType $type */
        $type = $this->type('Example', <<<'GraphQL'
            type Example
            GraphQL);

        self::assertSame('Example', $type->getName());
    }

    public function testEmptyDescription(): void
    {
        /** @var ObjectType $type */
        $type = $this->type('Example', <<<'GraphQL'
            type Example
            GraphQL);

        self::assertNull($type->getDescription());
    }

    public function testInlineDescription(): void
    {
        /** @var ObjectType $type */
        $type = $this->type('Example', <<<'GraphQL'
            "example description"
            type Example
            GraphQL);

        self::assertSame('example description', $type->getDescription());
    }

    public function testMultilineDescription(): void
    {
        /** @var ObjectType $type */
        $type = $this->type('Example', <<<'GraphQL'
            """
            example multiline description
            """
            type Example
            GraphQL);

        self::assertSame("example multiline description", $type->getDescription());
    }

    public function testNoFields(): void
    {
        /** @var ObjectType $type */
        $type = $this->type('Example', <<<'GraphQL'
            type Example
            GraphQL);

        self::assertSame(0, $type->getNumberOfFields());
        self::assertCount(0, $type->getFields());
    }

    public function testFieldName(): void
    {
        /** @var ObjectType $type */
        $type = $this->type('Example', <<<'GraphQL'
            type Example {
                EXAMPLE: String
            }
            GraphQL);

        $field = $type->getField('EXAMPLE');

        self::assertNotNull($field);
        self::assertNull($type->getField('example'));
        self::assertSame('EXAMPLE', $field->getName());
    }
}
