<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\Directive\Location;
use Railt\Reflection\Contracts\Types\DirectiveType;

/**
 * Class DirectiveTestCase
 */
class DirectiveTestCase extends AbstractReflectionTestCase
{
    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {

        $schema = <<<GraphQL
"""
 # This is a test directive allowed for GraphQL SDL (an Interface definition) 
 # and GraphQL Queries (The mutation action).
"""        
directive @test on MUTATION, INTERFACE 

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

        return [
            [$this->getDocument($schema)],
            [$this->getCachedDocument($schema)],
        ];
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testDirective(Document $document): void
    {
        static::assertNotNull($document->getType('test'));
        static::assertNotNull($document->getType('some'));
        static::assertNull($document->getType('@test'));
        static::assertNull($document->getType('@some'));
        static::assertNull($document->getType('Test'));
        static::assertNull($document->getType('Some'));
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testDirectiveTest(Document $document): void
    {
        /** @var DirectiveType $directive */
        $directive = $document->getType('test');
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

        static::assertTrue($directive->isAllowedForQueries());
        static::assertTrue($directive->isAllowedForSchemaDefinitions());

        static::assertTrue($directive->isAllowedFor($document->getType('ExampleInterface')));
        static::assertFalse($directive->isAllowedFor($document->getType('ExampleObject')));
        static::assertFalse($directive->isAllowedFor($document->getType('UndefinedType')));
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testDirectiveSome(Document $document): void
    {
        /** @var DirectiveType $directive */
        $directive = $document->getType('some');
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

        static::assertFalse($directive->isAllowedForQueries());
        static::assertTrue($directive->isAllowedForSchemaDefinitions());

        static::assertTrue($directive->isAllowedFor($document->getType('ExampleObject')));
        static::assertFalse($directive->isAllowedFor($document->getType('ExampleInterface')));
        static::assertFalse($directive->isAllowedFor($document->getType('UndefinedType')));
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
        /** @var DirectiveType $some */
        $some = $document->getType('some');
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
        static::assertFalse($some->getArgument('opt')->isNonNullList());
        static::assertSame('String', $some->getArgument('opt')->getType()->getName());


        // Definition of `opt2: String`
        static::assertNotNull($some->getArgument('opt2'));
        static::assertTrue($some->hasArgument('opt2'));
        static::assertTrue($some->getArgument('opt2')->hasDefaultValue());
        static::assertNull($some->getArgument('opt2')->getDefaultValue());
        static::assertFalse($some->getArgument('opt2')->isNonNull());
        static::assertFalse($some->getArgument('opt2')->isList());
        static::assertFalse($some->getArgument('opt2')->isNonNullList());
        static::assertSame('String', $some->getArgument('opt2')->getType()->getName());

        // Definition of `req: ID!`
        static::assertNotNull($some->getArgument('req'));
        static::assertTrue($some->hasArgument('req'));
        static::assertFalse($some->getArgument('req')->hasDefaultValue());
        static::assertNull($some->getArgument('req')->getDefaultValue());
        static::assertTrue($some->getArgument('req')->isNonNull());
        static::assertFalse($some->getArgument('req')->isList());
        static::assertFalse($some->getArgument('req')->isNonNullList());
        static::assertSame('ID', $some->getArgument('req')->getType()->getName());

        // Undefined
        static::assertNull($some->getArgument('Opt'));
        static::assertFalse($some->hasArgument('Opt'));
    }
}
