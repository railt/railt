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
 * Class ListTypeNode
 *
 * <code>
 *  export type ListTypeNode = {
 *      +kind: 'ListType',
 *      +loc?: Location,
 *      +type: TypeNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L397
 */
final class ListTypeNode extends TypeNode
{
    /**
     * @var TypeNode
     */
    public TypeNode $type;

    /**
     * ListTypeNode constructor.
     *
     * @param TypeNode $type
     */
    public function __construct(TypeNode $type)
    {
        $this->type = $type;
    }
}
