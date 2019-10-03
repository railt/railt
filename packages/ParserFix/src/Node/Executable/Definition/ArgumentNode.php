<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Executable\Definition;

use Railt\Parser\Node\NameNode;
use Railt\Parser\Node\DefinitionNode;
use Railt\Parser\Node\Value\ValueNode;

/**
 * Class ArgumentNode
 *
 * <code>
 *  export type ArgumentNode = {
 *      +kind: 'Argument',
 *      +loc?: Location,
 *      +name: NameNode,
 *      +value: ValueNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L259
 */
class ArgumentNode extends DefinitionNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * @var ValueNode
     */
    public ValueNode $value;

    /**
     * ArgumentNode constructor.
     *
     * @param NameNode $name
     * @param ValueNode $value
     */
    public function __construct(NameNode $name, ValueNode $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
