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
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class ObjectExtensionTypeTestCase
 */
class ObjectExtensionTypeTestCase extends ObjectTypeTestCase
{
    /**
     * @return void
     */
    public function testObjectDescription(): void
    {
        $this->expectNotToPerformAssertions();
    }

    /**
     * @return Node|ObjectTypeDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function objectType(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            extend type Example implements Interface1 & Interface2 @typeDirective(a: 23) {
                "field description"
                field(
                    "argument description" 
                    argument: ArgumentType = "argument default"
                        @argumentDirective(b: 42)
                ): FieldType
                    @fieldDirective(c: 666)
            }
        GraphQL
        );
    }
}
