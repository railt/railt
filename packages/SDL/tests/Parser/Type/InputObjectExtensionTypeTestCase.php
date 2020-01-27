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
use Railt\SDL\Frontend\Ast\Definition\Type\InputObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class InputObjectExtensionTypeTestCase
 */
class InputObjectExtensionTypeTestCase extends InputObjectTypeTestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function testInputObjectDescription(): void
    {
        $this->expectNotToPerformAssertions();
    }

    /**
     * @return Node|InputObjectTypeDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function inputObjectType(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            extend input Example @typeDirective(a: 23) {
                "field description"
                field: FieldType! = {b: 42}
                    @fieldDirective(c: 666)
            }
        GraphQL);
    }
}
