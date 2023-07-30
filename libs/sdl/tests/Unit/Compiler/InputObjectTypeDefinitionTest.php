<?php

declare(strict_types=1);

namespace Railt\SDL\Tests\Unit\Compiler;

use PHPUnit\Framework\Attributes\Group;
use Railt\TypeSystem\Definition\Type\InputObjectType;

#[Group('railt/sdl')]
final class InputObjectTypeDefinitionTest extends TestCase
{
    public function testName(): void
    {
        /** @var InputObjectType $input */
        $input = $this->type('Example', <<<'GraphQL'
            input Example
            GraphQL);

        self::assertSame('Example', $input->getName());
    }

    public function testEmptyDescription(): void
    {
        /** @var InputObjectType $input */
        $input = $this->type('Example', <<<'GraphQL'
            input Example
            GraphQL);

        self::assertNull($input->getDescription());
    }

    public function testInlineDescription(): void
    {
        /** @var InputObjectType $input */
        $input = $this->type('Example', <<<'GraphQL'
            "example description"
            input Example
            GraphQL);

        self::assertSame('example description', $input->getDescription());
    }

    public function testMultilineDescription(): void
    {
        /** @var InputObjectType $input */
        $input = $this->type('Example', <<<'GraphQL'
            """
            example multiline description
            """
            input Example
            GraphQL);

        self::assertSame("example multiline description", $input->getDescription());
    }

    public function testNoFields(): void
    {
        /** @var InputObjectType $input */
        $input = $this->type('Example', <<<'GraphQL'
            input Example
            GraphQL);

        self::assertSame(0, $input->getNumberOfFields());
        self::assertCount(0, $input->getFields());
    }

    public function testInputFieldName(): void
    {
        /** @var InputObjectType $input */
        $input = $this->type('Example', <<<'GraphQL'
            input Example {
                EXAMPLE: String
            }
            GraphQL);

        $field = $input->getField('EXAMPLE');

        self::assertNotNull($field);
        self::assertNull($input->getField('example'));
        self::assertSame('EXAMPLE', $field->getName());
    }
}
