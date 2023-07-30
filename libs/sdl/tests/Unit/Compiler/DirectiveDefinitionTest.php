<?php

declare(strict_types=1);

namespace Railt\SDL\Tests\Unit\Compiler;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Exception\TypeAlreadyDefinedException;
use Railt\TypeSystem\Definition\DirectiveDefinition;
use Railt\TypeSystem\Definition\DirectiveLocation;
use Railt\TypeSystem\Definition\Type\ScalarType;

#[Group('railt/sdl')]
final class DirectiveDefinitionTest extends TestCase
{
    public function testName(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example on OBJECT
            GraphQL);

        self::assertSame('example', $directive->getName());
    }

    public function testEmptyDescription(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example on OBJECT
            GraphQL);

        self::assertNull($directive->getDescription());
    }

    public function testInlineDescription(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            "example description"
            directive @example on OBJECT
            GraphQL);

        self::assertSame('example description', $directive->getDescription());
    }

    public function testMultilineDescription(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            """
            example multiline description
            """
            directive @example on OBJECT
            GraphQL);

        self::assertSame("example multiline description", $directive->getDescription());
    }

    public function testDirectiveRedefine(): void
    {
        $this->expectException(TypeAlreadyDefinedException::class);
        $this->expectExceptionMessage('already defined directive "@example"');

        $this->compile(<<<'GraphQL'
            directive @example on OBJECT
            directive @example on OBJECT
            GraphQL);
    }

    public static function directiveLocationsDataProvider(): array
    {
        $locations = [];

        foreach (DirectiveLocation::cases() as $case) {
            $locations[$case->getName()] = [$case->getName()];
        }

        return $locations;
    }

    #[DataProvider('directiveLocationsDataProvider')]
    public function testLocation(string $name): void
    {
        $location = DirectiveLocation::tryFromName($name);
        self::assertNotNull($location);

        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<GraphQL
            directive @example on $name
            GraphQL);

        self::assertCount(1, $directive->getLocations());
        self::assertSame(1, $directive->getNumberOfLocations());
        self::assertSame($location, [...$directive->getLocations()][0]);
    }

    public function testNotRepeatable(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example on OBJECT
            GraphQL);

        self::assertFalse($directive->isRepeatable());
    }

    public function testRedefinedLocation(): void
    {
        $this->expectException(CompilationException::class);
        $this->expectExceptionMessage('already defined location "OBJECT"');

        $this->compile(<<<'GraphQL'
            directive @example on OBJECT | OBJECT
            GraphQL);
    }

    public function testRepeatable(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example repeatable on OBJECT
            GraphQL);

        self::assertTrue($directive->isRepeatable());
    }

    public function testNoArguments(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example repeatable on OBJECT
            GraphQL);

        self::assertSame(0, $directive->getNumberOfArguments());
    }

    public function testOneArgument(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example(arg: String) on OBJECT
            GraphQL);

        self::assertSame(1, $directive->getNumberOfArguments());

        self::assertNull($directive->getArgument('Arg'));
        self::assertNotNull($directive->getArgument('arg'));
    }

    public function testArgumentType(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example(arg: String) on OBJECT
            GraphQL);

        $argument = $directive->getArgument('arg');
        self::assertNotNull($argument);

        self::assertInstanceOf(ScalarType::class, $argument->getType());
        self::assertSame('String', $argument->getType()->getName());
    }

    public function testArgumentNoDescription(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example(arg: String) on OBJECT
            GraphQL);

        $argument = $directive->getArgument('arg');

        self::assertNotNull($argument);
        self::assertNull($argument->getDescription());
    }

    public function testArgumentInlineDescription(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example("description" arg: String) on OBJECT
            GraphQL);

        $argument = $directive->getArgument('arg');

        self::assertNotNull($argument);
        self::assertSame('description', $argument->getDescription());
    }

    public function testArgumentMultilineDescription(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example(
                """
                description
                """
                arg: String
            ) on OBJECT
            GraphQL);

        $argument = $directive->getArgument('arg');

        self::assertNotNull($argument);
        self::assertSame("description", $argument->getDescription());
    }

    public function testArgumentNoDefault(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example(arg: String) on OBJECT
            GraphQL);

        $argument = $directive->getArgument('arg');

        self::assertNotNull($argument);
        self::assertFalse($argument->hasDefaultValue());
        self::assertNull($argument->getDefaultValue());
    }

    public function testArgumentDefaultString(): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $this->directive('example', <<<'GraphQL'
            directive @example(arg: String = "string") on OBJECT
            GraphQL);

        $argument = $directive->getArgument('arg');

        self::assertNotNull($argument);
        self::assertTrue($argument->hasDefaultValue());
        self::assertSame('string', $argument->getDefaultValue());
    }

    public function testInvalidDefault(): void
    {
        $this->expectException(CompilationException::class);
        $this->expectExceptionMessage('non-string value');

        $this->compile(<<<'GraphQL'
            directive @example(arg: String = {}) on OBJECT
            GraphQL);
    }

    public function testRedefineArgument(): void
    {
        $this->expectException(CompilationException::class);
        $this->expectExceptionMessage('already defined argument "arg"');

        $this->compile(<<<'GraphQL'
            directive @example(arg: String, arg: Int) on OBJECT
            GraphQL);
    }
}
