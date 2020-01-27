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
use Railt\SDL\Frontend\Ast\Definition\Type\UnionTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class ScalarExtensionTypeTestCase
 */
class ScalarExtensionTypeTestCase extends ScalarTypeTestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function testUnionDescription(): void
    {
        $this->expectNotToPerformAssertions();
    }

    /**
     * @return Node|UnionTypeDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function unionType(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            extend scalar Example @typeDirective(a: 23)
        GraphQL
        );
    }
}
