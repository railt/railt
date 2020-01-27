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
use Railt\SDL\Frontend\Ast\Definition\Type\UnionTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Tests\Parser\ParserTestCase;
use Railt\TypeSystem\Value\IntValue;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class ScalarTypeTestCase
 */
class ScalarTypeTestCase extends ParserTestCase
{
    /**
     * @return void
     * @throws ParserRuntimeExceptionInterface
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function testUnionDescription(): void
    {
        $type = $this->unionType();

        $this->assertSame('Type description', \trim($type->description->value));
    }

    /**
     * @return Node|UnionTypeDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function unionType(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            """
            Type description
            """
            scalar Example @typeDirective(a: 23)
        GraphQL);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testUnionName(): void
    {
        $type = $this->unionType();

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
    public function testUnionDirectivesCount(): void
    {
        $type = $this->unionType();

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
    public function testUnionDirectiveName(): void
    {
        $type = $this->unionType();

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
    public function testUnionDirectivesArgumentsCount(): void
    {
        $type = $this->unionType();

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
    public function testUnionDirectivesArgumentName(): void
    {
        $type = $this->unionType();

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
    public function testUnionDirectivesArgumentValue(): void
    {
        $type = $this->unionType();

        $this->assertEquals(new IntValue(23), $type->directives[0]->arguments[0]->value);
    }
}
