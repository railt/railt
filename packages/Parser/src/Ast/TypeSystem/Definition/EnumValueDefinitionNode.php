<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast\TypeSystem\Definition;

use Railt\Parser\Ast\NameNode;
use Railt\Parser\Ast\DefinitionNode;
use Railt\Parser\Ast\Value\StringValueNode;
use Railt\Parser\Ast\Generic\DirectiveCollection;

/**
 * Class EnumValueDefinitionNode
 *
 * <code>
 *  export type EnumValueDefinitionNode = {
 *      +kind: 'EnumValueDefinition',
 *      +loc?: Location,
 *      +description?: StringValueNode,
 *      +name: NameNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L516
 */
class EnumValueDefinitionNode extends DefinitionNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * @var StringValueNode|null
     */
    public ?StringValueNode $description = null;

    /**
     * @var DirectiveCollection|null
     */
    public ?DirectiveCollection $directives = null;

    /**
     * EnumValueDefinitionNode constructor.
     *
     * @param NameNode $name
     */
    public function __construct(NameNode $name)
    {
        $this->name = $name;
    }
}
