<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast;

/**
 * Class DefinitionNode
 *
 * <code>
 *  export type DefinitionNode =
 *      | ExecutableDefinitionNode
 *      | TypeSystemDefinitionNode
 *      | TypeSystemExtensionNode
 *  ;
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L200
 */
abstract class DefinitionNode extends Node
{
    /**
     * @var array
     */
    public array $attributes = [];

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        yield from parent::getIterator();

        yield 'attributes' => $this->attributes;
    }
}
