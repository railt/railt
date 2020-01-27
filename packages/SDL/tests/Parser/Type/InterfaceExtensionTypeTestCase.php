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
use Railt\SDL\Frontend\Ast\Definition\Type\InterfaceTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class InterfaceExtensionTypeTestCase
 */
class InterfaceExtensionTypeTestCase extends InterfaceTypeTestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function testInterfaceDescription(): void
    {
        $this->expectNotToPerformAssertions();
    }

    /**
     * @return Node|InterfaceTypeDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function interfaceType(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            extend interface Example implements Interface1 & Interface2 @typeDirective(a: 23) {
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
