<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Type;

use Railt\Parser\Node\NameNode;

/**
 * Class NamedTypeNode
 *
 * <code>
 *  export type NamedTypeNode = {
 *      +kind: 'NamedType',
 *      +loc?: Location,
 *      +name: NameNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L390
 */
final class NamedTypeNode extends TypeNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * NamedType constructor.
     *
     * @param NameNode $name
     */
    public function __construct(NameNode $name)
    {
        $this->name = $name;
    }
}
