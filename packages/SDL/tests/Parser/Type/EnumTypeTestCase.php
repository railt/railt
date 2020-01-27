<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests\Parser\Type;

use Phplrt\Contracts\Parser\Exception\ParserRuntimeExceptionInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\SDL\Frontend\Ast\Definition\Type\EnumTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Tests\Parser\ParserTestCase;
use Railt\TypeSystem\Value\IntValue;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class EnumTypeTestCase
 */
class EnumTypeTestCase extends ParserTestCase
{
    /**
     * @return void
     * @throws ParserRuntimeExceptionInterface
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function testEnumDescription(): void
    {
        $type = $this->enumType();

        $this->assertSame('Type description', \trim($type->description->value));
    }

    /**
     * @return Node|EnumTypeDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function enumType(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            """
            Type description
            """
            enum Example @typeDirective(a: 23) {
                "value description"
                VALUE @valueDirective(c: 666)
            }
        GraphQL);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testEnumName(): void
    {
        $type = $this->enumType();

        $this->assertSame('Example', $type->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testEnumDirectivesCount(): void
    {
        $type = $this->enumType();

        $this->assertCount(1, $type->directives);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testEnumDirectiveName(): void
    {
        $type = $this->enumType();

        $this->assertSame('typeDirective', $type->directives[0]->name->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testEnumDirectivesArgumentsCount(): void
    {
        $type = $this->enumType();

        $this->assertCount(1, $type->directives[0]->arguments);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testEnumDirectivesArgumentName(): void
    {
        $type = $this->enumType();

        $this->assertSame('a', $type->directives[0]->arguments[0]->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws Exception
     * @throws \Throwable
     */
    public function testEnumDirectivesArgumentValue(): void
    {
        $type = $this->enumType();

        $this->assertEquals(new IntValue(23), $type->directives[0]->arguments[0]->value);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testEnumValuesCount(): void
    {
        $type = $this->enumType();

        $this->assertCount(1, $type->values);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testEnumValueName(): void
    {
        $type = $this->enumType();

        $this->assertSame('VALUE', $type->values[0]->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testEnumValueDescription(): void
    {
        $type = $this->enumType();

        $this->assertSame('value description', $type->values[0]->description->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testEnumValueDirectivesCount(): void
    {
        $type = $this->enumType();

        $this->assertCount(1, $type->values[0]->directives);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testEnumValueDirectiveName(): void
    {
        $type = $this->enumType();

        $this->assertSame('valueDirective', $type->values[0]->directives[0]->name->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testEnumValueDirectiveArgumentsCount(): void
    {
        $type = $this->enumType();

        $this->assertCount(1, $type->values[0]->directives[0]->arguments);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testEnumValueDirectiveArgumentName(): void
    {
        $type = $this->enumType();

        $this->assertSame('c', $type->values[0]->directives[0]->arguments[0]->name->value);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testEnumValueDirectiveArgumentValue(): void
    {
        $type = $this->enumType();

        $this->assertEquals(new IntValue(666), $type->values[0]->directives[0]->arguments[0]->value);
    }
}
