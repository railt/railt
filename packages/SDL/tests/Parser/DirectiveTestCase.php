<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests\Parser;

use Phplrt\Contracts\Parser\Exception\ParserRuntimeExceptionInterface;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\IntValue;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class DirectiveTestCase
 */
class DirectiveTestCase extends ParserTestCase
{
    /**
     * @return void
     * @throws ParserRuntimeExceptionInterface
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function testDirectiveDescription(): void
    {
        $type = $this->directive();

        $this->assertSame('Directive description', $type->description->value);
    }

    /**
     * @return Node|DirectiveDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function directive(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            """Directive description"""
            directive @example(
                "a description" a: String = 23
                "b description" b: Int = 42
            ) repeatable on 
                | INPUT_OBJECT
                | FIELD_DEFINITION
        GraphQL
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveName(): void
    {
        $type = $this->directive();

        $this->assertSame('example', $type->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveIsRepeatable(): void
    {
        $type = $this->directive();

        $this->assertNotNull($type->repeatable);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveLocationsCount(): void
    {
        $type = $this->directive();

        $this->assertCount(2, $type->locations);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveLocationNames(): void
    {
        $type = $this->directive();

        $this->assertSame('INPUT_OBJECT', $type->locations[0]->name->value);
        $this->assertSame('FIELD_DEFINITION', $type->locations[1]->name->value);
    }


    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveArgumentsCount(): void
    {
        $type = $this->directive();

        $this->assertCount(2, $type->arguments);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveFirstArgumentName(): void
    {
        $type = $this->directive();

        $this->assertSame('a', $type->arguments[0]->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveFirstArgumentDescription(): void
    {
        $type = $this->directive();

        $this->assertSame('a description', $type->arguments[0]->description->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveFirstArgumentValue(): void
    {
        $type = $this->directive();

        $this->assertEquals(new IntValue(23), $type->arguments[0]->defaultValue);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveFirstArgumentType(): void
    {
        $type = $this->directive();

        $this->assertSame('String', $type->arguments[0]->type->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveSecondArgumentName(): void
    {
        $type = $this->directive();

        $this->assertSame('b', $type->arguments[1]->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveSecondArgumentDescription(): void
    {
        $type = $this->directive();

        $this->assertSame('b description', $type->arguments[1]->description->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveSecondArgumentValue(): void
    {
        $type = $this->directive();

        $this->assertEquals(new IntValue(42), $type->arguments[1]->defaultValue);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testDirectiveSecondArgumentType(): void
    {
        $type = $this->directive();

        $this->assertSame('Int', $type->arguments[1]->type->name->value);
    }
}
