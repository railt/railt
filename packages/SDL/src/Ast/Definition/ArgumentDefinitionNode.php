<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\SDL\Ast\Name\IdentifierNode;
use Railt\SDL\Ast\Type\TypeNode;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Value\ValueNode;
use Railt\SDL\Ast\Value\StringValueNode;
use Railt\SDL\Ast\Generic\DirectiveCollection;

/**
 * Class ArgumentDefinitionNode
 */
class ArgumentDefinitionNode extends DefinitionNode
{
    /**
     * @var IdentifierNode
     */
    public IdentifierNode $name;

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
     * @var ValueNode|null
     */
    public ?ValueNode $defaultValue = null;

    /**
     * TypeDefinitionNode constructor.
     *
     * @param IdentifierNode $name
     * @param TypeNode $type
     */
    public function __construct(IdentifierNode $name, TypeNode $type)
    {
        $this->name = $name;
        $this->type = $type;
    }
}
