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
use Railt\SDL\Frontend\Ast\Definition\Type\EnumTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class EnumExtensionTypeTestCase
 */
class EnumExtensionTypeTestCase extends EnumTypeTestCase
{
    /**
     * @return void
     */
    public function testEnumDescription(): void
    {
        $this->expectNotToPerformAssertions();
    }

    /**
     * @return Node|EnumTypeDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function enumType(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            extend enum Example @typeDirective(a: 23) {
                "value description"
                VALUE @valueDirective(c: 666)
            }
        GraphQL);
    }
}
