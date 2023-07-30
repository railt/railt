<?php

declare(strict_types=1);

namespace Railt\SDL\Tests\Unit\Compiler;

use PHPUnit\Framework\Attributes\Group;
use Railt\TypeSystem\Definition\Type\UnionType;

#[Group('railt/sdl')]
final class UnionTypeDefinitionTest extends TestCase
{
    public function testName(): void
    {
        /** @var UnionType $union */
        $union = $this->type('Example', <<<'GraphQL'
            type Obj
            union Example = Obj
            GraphQL);

        self::assertSame('Example', $union->getName());
    }

    public function testEmptyDescription(): void
    {
        /** @var UnionType $union */
        $union = $this->type('Example', <<<'GraphQL'
            type Obj
            union Example = Obj
            GraphQL);

        self::assertNull($union->getDescription());
    }

    public function testInlineDescription(): void
    {
        /** @var UnionType $union */
        $union = $this->type('Example', <<<'GraphQL'
            type Obj
            "example description"
            union Example = Obj
            GraphQL);

        self::assertSame('example description', $union->getDescription());
    }

    public function testMultilineDescription(): void
    {
        /** @var UnionType $union */
        $union = $this->type('Example', <<<'GraphQL'
            type Obj
            """
            example multiline description
            """
            union Example = Obj
            GraphQL);

        self::assertSame("example multiline description", $union->getDescription());
    }
}
