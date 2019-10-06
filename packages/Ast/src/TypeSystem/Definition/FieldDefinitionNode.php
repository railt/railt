<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Ast\TypeSystem\Definition;

use Railt\Ast\Node;
use Railt\Ast\NameNode;
use Railt\Ast\Type\TypeNode;
use Railt\Ast\DefinitionNode;
use Railt\Ast\Value\StringValueNode;
use Railt\Ast\Generic\DirectiveCollection;
use Railt\Ast\Generic\InputValueDefinitionCollection;

/**
 * Class FieldDefinition
 *
 * <code>
 *  export type FieldDefinitionNode = {
 *      +kind: 'FieldDefinition',
 *      +loc?: Location,
 *      +description?: StringValueNode,
 *      +name: NameNode,
 *      +arguments?: $ReadOnlyArray<InputValueDefinitionNode>,
 *      +type: TypeNode,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L464
 */
class FieldDefinitionNode extends DefinitionNode
{
    /**
     * @var NameNode
     */
    public NameNode $name;

    /**
     * @var TypeNode
     */
    public TypeNode $type;

    /**
     * @var StringValueNode|null
     */
    public ?StringValueNode $description = null;

    /**
     * @var DirectiveCollection|null
     */
    public ?DirectiveCollection $directives = null;

    /**
     * @var InputValueDefinitionCollection|null
     */
    public ?InputValueDefinitionCollection $arguments = null;

    /**
     * TypeDefinitionNode constructor.
     *
     * @param NameNode $name
     * @param TypeNode $type
     */
    public function __construct(NameNode $name, TypeNode $type)
    {
        $this->name = $name;
        $this->type = $type;
    }
}
