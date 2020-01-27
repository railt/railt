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
use Railt\SDL\Frontend\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class SchemaExtensionTestCase
 */
class SchemaExtensionTestCase extends SchemaTestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function testSchemaDescription(): void
    {
        $this->expectNotToPerformAssertions();
    }

    /**
     * @return Node|SchemaDefinitionNode
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function schema(): Node
    {
        return $this->parseFirst(<<<'GraphQL'
            extend schema @schemaDirective(a: 23) {
                query: Query
                mutation: Mutation
                subscription: Subscription
            }
        GraphQL
        );
    }
}
