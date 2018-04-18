<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Directives;

use Railt\Io\File;
use Railt\Reflection\Contracts\Definitions\Directive\Location;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\SDL\Schema\CompilerInterface;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class DirectiveTestCase
 */
class DirectiveTestCase extends AbstractLanguageTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provider(): array
    {
        $schema = <<<'GraphQL'
"""
 # This is a test directive allowed for GraphQL SDL (an Interface definition) 
 # and GraphQL Queries (The mutation action).
"""        
directive @test on MUTATION | INTERFACE 

"""
 # This is a test directive allowed only for GraphQL SDL (an Object type definition).
"""
directive @some(opt: String! = "Example", opt2: String, req: ID!) on OBJECT

#
# There are an object and interface types using only _for_tests_.
# All interfaces and object tests defined in same XxxTestCase classes.
#
type ExampleObject {}
interface ExampleInterface {}
GraphQL;

        return $this->dataProviderDocuments($schema);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testDirective(Document $document): void
    {
        static::assertNotNull($document->getTypeDefinition('test'));
        static::assertNotNull($document->getTypeDefinition('some'));
        static::assertNull($document->getTypeDefinition('@test'));
        static::assertNull($document->getTypeDefinition('@some'));
        static::assertNull($document->getTypeDefinition('Test'));
        static::assertNull($document->getTypeDefinition('Some'));
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDirectiveTest(Document $document): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $document->getTypeDefinition('test');
        static::assertNotNull($directive);

        static::assertSame('test', $directive->getName());
        $description =
            'This is a test directive allowed for GraphQL SDL (an Interface definition)' . "\n" .
            'and GraphQL Queries (The mutation action).';
        static::assertSame($description, $directive->getDescription());
        static::assertFalse($directive->isDeprecated());
        static::assertSame('', $directive->getDeprecationReason());

        /**
         * LOCATIONS
         */
        foreach (Location::TARGET_GRAPHQL_SDL as $location) {
            if ($location === Location::TARGET_INTERFACE) {
                static::assertTrue($directive->hasLocation($location));
            } else {
                static::assertFalse($directive->hasLocation($location));
            }
        }

        foreach (Location::TARGET_GRAPHQL_QUERY as $location) {
            if ($location === Location::TARGET_MUTATION) {
                static::assertTrue($directive->hasLocation($location));
            } else {
                static::assertFalse($directive->hasLocation($location));
            }
        }

        static::assertCount(2, $directive->getLocations());

        static::assertTrue($directive->isAllowedForQueries());
        static::assertTrue($directive->isAllowedForSchemaDefinitions());

        static::assertTrue($directive->isAllowedFor($document->getTypeDefinition('ExampleInterface')));
        static::assertFalse($directive->isAllowedFor($document->getTypeDefinition('ExampleObject')));
        static::assertFalse($directive->isAllowedFor($document->getTypeDefinition('UndefinedType')));
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDirectiveSome(Document $document): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $document->getTypeDefinition('some');
        static::assertNotNull($directive);

        static::assertSame('some', $directive->getName());
        $description =
            'This is a test directive allowed only for GraphQL SDL (an Object type definition).';
        static::assertSame($description, $directive->getDescription());
        static::assertFalse($directive->isDeprecated());
        static::assertSame('', $directive->getDeprecationReason());

        /**
         * LOCATIONS
         */
        foreach (Location::TARGET_GRAPHQL_SDL as $location) {
            if ($location === Location::TARGET_OBJECT) {
                static::assertTrue($directive->hasLocation($location));
            } else {
                static::assertFalse($directive->hasLocation($location));
            }
        }

        foreach (Location::TARGET_GRAPHQL_QUERY as $location) {
            static::assertFalse($directive->hasLocation($location));
        }

        static::assertCount(1, $directive->getLocations());

        static::assertFalse($directive->isAllowedForQueries());
        static::assertTrue($directive->isAllowedForSchemaDefinitions());

        static::assertTrue($directive->isAllowedFor($document->getTypeDefinition('ExampleObject')));
        static::assertFalse($directive->isAllowedFor($document->getTypeDefinition('ExampleInterface')));
        static::assertFalse($directive->isAllowedFor($document->getTypeDefinition('UndefinedType')));
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testArguments(Document $document): void
    {
        /** @var DirectiveDefinition $some */
        $some = $document->getTypeDefinition('some');
        static::assertNotNull($some);

        static::assertCount(3, $some->getArguments());
        static::assertCount($some->getNumberOfArguments(), $some->getArguments());
        static::assertSame(3, $some->getNumberOfArguments());
        static::assertSame(2, $some->getNumberOfOptionalArguments());
        static::assertSame(1, $some->getNumberOfRequiredArguments());

        // Definition of `opt: String! = "Example"`
        static::assertNotNull($some->getArgument('opt'));
        static::assertTrue($some->hasArgument('opt'));
        static::assertTrue($some->getArgument('opt')->hasDefaultValue());
        static::assertSame('Example', $some->getArgument('opt')->getDefaultValue());
        static::assertTrue($some->getArgument('opt')->isNonNull());
        static::assertFalse($some->getArgument('opt')->isList());
        static::assertFalse($some->getArgument('opt')->isListOfNonNulls());
        static::assertSame('String', $some->getArgument('opt')->getTypeDefinition()->getName());


        // Definition of `opt2: String`
        static::assertNotNull($some->getArgument('opt2'));
        static::assertTrue($some->hasArgument('opt2'));
        static::assertTrue($some->getArgument('opt2')->hasDefaultValue());
        static::assertNull($some->getArgument('opt2')->getDefaultValue());
        static::assertFalse($some->getArgument('opt2')->isNonNull());
        static::assertFalse($some->getArgument('opt2')->isList());
        static::assertFalse($some->getArgument('opt2')->isListOfNonNulls());
        static::assertSame('String', $some->getArgument('opt2')->getTypeDefinition()->getName());

        // Definition of `req: ID!`
        static::assertNotNull($some->getArgument('req'));
        static::assertTrue($some->hasArgument('req'));
        static::assertFalse($some->getArgument('req')->hasDefaultValue());
        static::assertNull($some->getArgument('req')->getDefaultValue());
        static::assertTrue($some->getArgument('req')->isNonNull());
        static::assertFalse($some->getArgument('req')->isList());
        static::assertFalse($some->getArgument('req')->isListOfNonNulls());
        static::assertSame('ID', $some->getArgument('req')->getTypeDefinition()->getName());

        // Undefined
        static::assertNull($some->getArgument('Opt'));
        static::assertFalse($some->hasArgument('Opt'));
    }

    /**
     * @dataProvider dateCompilersProvider
     *
     * @param CompilerInterface $compiler
     * @return void
     */
    public function testInvalidSelfReferencingDirective(CompilerInterface $compiler): void
    {
        $this->expectException(TypeConflictException::class);

        $compiler->compile(File::fromSources(<<<'GraphQL'
directive @invalidExample(
    arg: String @invalidExample(arg: "references itself")
) on ARGUMENT_DEFINITION
GraphQL
        ));
    }

    /**
     * @dataProvider dateCompilersProvider
     *
     * @param CompilerInterface $compiler
     * @return void
     */
    public function testValidDirectiveOnDirectiveUsage(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(<<<'GraphQL'
directive @validExample(
    arg: String @deprecated
) on ARGUMENT_DEFINITION
GraphQL
        ));

        /** @var DirectiveDefinition $directive */
        $directive = $document->getTypeDefinition('validExample');

        static::assertInstanceOf(DirectiveDefinition::class, $directive);

        /** @var ArgumentDefinition $arg */
        $arg = $directive->getArgument('arg');

        static::assertInstanceOf(ArgumentDefinition::class, $arg);

        static::assertSame(1, $arg->getNumberOfDirectives());
        static::assertCount(1, $arg->getDirectives());
        static::assertSame('deprecated', $arg->getDirective('deprecated')->getName());
        static::assertTrue($arg->isDeprecated());
    }
}
