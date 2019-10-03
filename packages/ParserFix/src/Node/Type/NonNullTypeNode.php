<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Type;

/**
 * Class NonNullTypeNode
 *
 * <code>
 *  export type NonNullTypeNode = {
 *      +kind: 'NonNullType',
 *      +loc?: Location,
 *      +type: NamedTypeNode | ListTypeNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L404
 */
final class NonNullTypeNode extends TypeNode
{
    /**
     * @var TypeNode
     */
    public TypeNode $type;

    /**
     * NonNullTypeNode constructor.
     *
     * @param TypeNode $type
     */
    public function __construct(TypeNode $type)
    {
        \assert(! $type instanceof self);

        $this->type = $type;
    }
}
