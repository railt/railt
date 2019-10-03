<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\TypeSystem\Definition;

use Railt\Parser\Node\Node;
use Railt\Parser\Node\NameNode;
use Railt\Parser\Node\Type\TypeNode;
use Railt\Parser\Node\DefinitionNode;
use Railt\Parser\Node\Value\StringValueNode;
use Railt\Parser\Node\Generic\DirectiveCollection;
use Railt\Parser\Node\Generic\InputValueDefinitionCollection;

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
