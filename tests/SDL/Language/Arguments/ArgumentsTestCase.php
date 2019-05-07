<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Arguments;

use Phplrt\Io\File;
use Railt\SDL\Compiler;
use Railt\SDL\Contracts\Definitions\ObjectDefinition;
use Railt\SDL\Contracts\Dependent\ArgumentDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Contracts\Invocations\DirectiveInvocation;
use Railt\SDL\Contracts\Invocations\InputInvocation;
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\SDL\Schema\CompilerInterface;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class ArgumentDefaultsTestCase
 */
class ArgumentsTestCase extends AbstractLanguageTestCase
{
    private const ARGUMENT_BODY = 'type A { field(argument: %s): String }';

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\SDL\Exceptions\CompilerException
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws \Railt\SDL\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {
        return \array_merge($this->positiveProvider(), $this->negativeProvider());
    }

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\SDL\Exceptions\CompilerException
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws \Railt\SDL\Exceptions\UnrecognizedTokenException
     */
    public function positiveProvider(): array
    {
        $schemas = [
            'String = null',
            '[String] = null',
            '[String!] = null',
            '[String]! = [null]',
            // NonNullList init by NULL will be coerced to [NULL]
            '[String]! = null',
            '[Int!]! = [1,2,3]',
            '[String!] = ["1","2","3"]',
        ];

        $result = [];

        foreach ($schemas as $schema) {
            $result[] = [\sprintf(self::ARGUMENT_BODY, $schema)];
        }

        return $result;
    }

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\SDL\Exceptions\CompilerException
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws \Railt\SDL\Exceptions\UnrecognizedTokenException
     */
    public function negativeProvider(): array
    {
        $schemas = [
            // NonNull init by NULL
            'String! = null',
            // NonList init by List
            'String = []',
            // ListOfNonNull init by List with NULL
            '[String!] = [1,null,3]',
        ];

        $result = [];

        foreach ($schemas as $schema) {
            $result[] = [\sprintf(self::ARGUMENT_BODY, $schema)];
        }

        return $result;
    }

    /**
     * @dataProvider positiveProvider
     *
     * @param string $schema
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Throwable
     */
    public function testAllowedArgumentDefaultValue(string $schema): void
    {
        try {
            foreach ($this->getDocuments($schema) as $document) {
                /** @var ObjectDefinition $type */
                $type = $document->getTypeDefinition('A');
                static::assertNotNull($type, 'Type "A" not found');

                /** @var FieldDefinition $field */
                $field = $type->getField('field');
                static::assertNotNull($field, 'Field "field" not found');

                /** @var ArgumentDefinition $argument */
                $argument = $field->getArgument('argument');
                static::assertNotNull($argument, 'Argument "argument" not found');
            }
        } catch (\Throwable $e) {
            static::assertFalse(true,
                'This code is valid: ' . "\n> " . $schema . "\n" .
                'But exception thrown:' . "\n> " . $e->getMessage()
            );
            throw $e;
        }
    }

    /**
     * @dataProvider negativeProvider
     *
     * @param string $schema
     * @return void
     * @throws \Exception
     */
    public function testInvalidArgumentDefaultValue(string $schema): void
    {
        $compilers = $this->getCompilers();

        /** @var Compiler $compiler */
        foreach ($compilers as $compiler) {
            try {
                $compiler->compile(File::fromSources($schema));
                static::assertFalse(true,
                    'Default value must throw an exception: ' . "\n" . $schema);
            } catch (TypeConflictException $error) {
                static::assertTrue(true);
            }
        }
    }

    /**
     * @dataProvider dateCompilersProvider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testValidInputArgumentType(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(<<<'GraphQL'
type User {}
input Where { field: String!, eq: Any, op: String! = "=" }

type UsersRepository {
    # Test input compatibility 
    find(where: Where! = {field: "id", eq: 42}): User
}
GraphQL
));
        /** @var ArgumentDefinition $arg */
        $arg = $document->getTypeDefinition('UsersRepository')
            ->getField('find')
            ->getArgument('where');

        $default = $arg->getDefaultValue();

        static::assertInstanceOf(InputInvocation::class, $default);

        static::assertArrayHasKey('field', $default);
        static::assertArrayHasKey('op', $default);
        static::assertArrayHasKey('eq', $default); // Resolved from Input

        static::assertSame('id', $default['field'] ?? null);
        static::assertSame('=', $default['op'] ?? null);
        static::assertSame(42, $default['eq'] ?? null);
    }

    /**
     * @dataProvider dateCompilersProvider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testValidInputArgumentListType(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(<<<'GraphQL'
type User {}
input Where { field: String!, eq: Any, op: String! = "=" }


type UsersRepository {
    # List allow defained by compatible list
    findAll(where: [Where!] = [{field: "id", eq: 42}]): [User!] 
}
GraphQL
        ));
        /** @var ArgumentDefinition $arg */
        $arg = $document->getTypeDefinition('UsersRepository')
            ->getField('findAll')
            ->getArgument('where');

        $default = $arg->getDefaultValue();

        static::assertInternalType('array', $default);

        foreach ((array)$default as $item) {
            static::assertInstanceOf(InputInvocation::class, $item);

            static::assertArrayHasKey('field', $item);
            static::assertArrayHasKey('op', $item);
            static::assertArrayHasKey('eq', $item); // Resolved from Input

            static::assertSame('id', $item['field'] ?? null);
            static::assertSame('=', $item['op'] ?? null);
            static::assertSame(42, $item['eq'] ?? null);
        }
    }

    /**
     * @dataProvider dateCompilersProvider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypeCoercionToList(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(<<<'GraphQL'
type User {}
input Where { field: String!, eq: Any, op: String! = "=" }

type UsersRepository {
    # {field: ...} should auto transform to [{field: ...}] 
    findAll(where: [Where!] = {field: "id", op: "<>", eq: 42}): [User!]
}
GraphQL
        ));
        /** @var ArgumentDefinition $arg */
        $arg = $document->getTypeDefinition('UsersRepository')
            ->getField('findAll')
            ->getArgument('where');

        $default = $arg->getDefaultValue();

        static::assertInternalType('array', $default);

        foreach ((array)$default as $item) {
            static::assertInstanceOf(InputInvocation::class, $item);

            static::assertArrayHasKey('field', $item);
            static::assertArrayHasKey('op', $item);
            static::assertArrayHasKey('eq', $item); // Resolved from Input

            static::assertSame('id', $item['field'] ?? null);
            static::assertSame('<>', $item['op'] ?? null);
            static::assertSame(42, $item['eq'] ?? null);
        }
    }

    /**
     * @dataProvider dateCompilersProvider
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testInputArgumentWithIncompatibleDefaultValue(CompilerInterface $compiler): void
    {
        $this->expectException(TypeConflictException::class);

        $compiler->compile(File::fromSources(<<<'GraphQL'
type User {}
input Where { field: String!, eq: Any, op: String! = "=" }


type UsersRepository {
    find(where: Where! = {some: "id"}): User # Field "some" does not exists in input "Where"
}
GraphQL
        ));
    }

    /**
     * @dataProvider dateCompilersProvider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testInvalidArgumentIntoDirective(CompilerInterface $compiler): void
    {
        $this->expectException(TypeConflictException::class);

        $compiler->compile(File::fromSources(<<<'GraphQL'
directive @some(foo: String) on OBJECT

type Other @some(bar: "Hey! Argument bar wasn't specified for this directive")  {
}
GraphQL
        ));
    }

    /**
     * @dataProvider dateCompilersProvider
     *
     * @param CompilerInterface $compiler
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testMissingArgumentIntoDirective(CompilerInterface $compiler): void
    {
        $this->expectException(TypeConflictException::class);

        $compiler->compile(File::fromSources(<<<'GraphQL'
directive @some(foo: String!) on OBJECT
type Example @some() {} # "foo" required 
GraphQL
        ));
    }

    /**
     * @dataProvider dateCompilersProvider
     *
     * @param CompilerInterface $compiler
     * @return void
     */
    public function testAutoExportArgumentsIntoDirective(CompilerInterface $compiler): void
    {
        $document = $compiler->compile(File::fromSources(<<<'GraphQL'
directive @test1(foo: String) on OBJECT
type Example1 @test1() {} 
    # No different from: type Example1 @test1(foo: NULL)

directive @test2(foo: String! = "some") on OBJECT
type Example2 @test2() {}
    # No different from: type Example1 @test2(foo: "some")
    

directive @test3(foo: Input3! = {foo: "23"}) on OBJECT
input Input3 { foo: String! }
type Example3 @test3() {} 
   # No different from: type Example1 @test2(foo: {foo: "23"})
GraphQL
        ));

        /** @var ObjectDefinition $example1 */
        $example1 = $document->getTypeDefinition('Example1');
        /** @var ObjectDefinition $example2 */
        $example2 = $document->getTypeDefinition('Example2');
        /** @var ObjectDefinition $example3 */
        $example3 = $document->getTypeDefinition('Example3');

        static::assertInstanceOf(ObjectDefinition::class, $example1);
        static::assertInstanceOf(ObjectDefinition::class, $example2);
        static::assertInstanceOf(ObjectDefinition::class, $example3);

        static::assertCount(1, $example1->getDirectives());
        static::assertCount(1, $example2->getDirectives());
        static::assertCount(1, $example3->getDirectives());

        /** @var DirectiveInvocation $test1 */
        $test1 = $example1->getDirective('test1');
        /** @var DirectiveInvocation $test2 */
        $test2 = $example2->getDirective('test2');
        /** @var DirectiveInvocation $test3 */
        $test3 = $example3->getDirective('test3');

        static::assertCount(1, $test1->getPassedArguments());
        static::assertCount(1, $test2->getPassedArguments());
        static::assertCount(1, $test3->getPassedArguments());
    }
}
