<?php

declare(strict_types=1);

namespace Railt\SDL\Tests\Compiler;

use PHPUnit\Framework\Attributes\Group;
use Railt\TypeSystem\ScalarTypeDefinition;

#[Group('unit'), Group('sdl')]
final class ScalarTypeDefinitionTest extends TestCase
{
    public function testName(): void
    {
        /** @var ScalarTypeDefinition $scalar */
        $scalar = $this->type('Example', <<<'GraphQL'
            scalar Example
            GraphQL);

        self::assertSame('Example', $scalar->getName());
    }

    public function testEmptyDescription(): void
    {
        /** @var ScalarTypeDefinition $scalar */
        $scalar = $this->type('Example', <<<'GraphQL'
            scalar Example
            GraphQL);

        self::assertNull($scalar->getDescription());
    }

    public function testInlineDescription(): void
    {
        /** @var ScalarTypeDefinition $scalar */
        $scalar = $this->type('Example', <<<'GraphQL'
            "example description"
            scalar Example
            GraphQL);

        self::assertSame('example description', $scalar->getDescription());
    }

    public function testMultilineDescription(): void
    {
        /** @var ScalarTypeDefinition $scalar */
        $scalar = $this->type('Example', <<<'GraphQL'
            """
            example multiline description
            """
            scalar Example
            GraphQL);

        self::assertSame("\nexample multiline description\n", $scalar->getDescription());
    }
}