<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast\TypeSystem\Definition;

use Railt\Parser\Ast\Node;
use Railt\Parser\Ast\DefinitionNode;
use Railt\Parser\Ast\Type\NamedTypeNode;
use Railt\Parser\Ast\Generic\DirectiveCollection;

/**
 * Class OperationTypeDefinitionNode
 *
 * <code>
 *  export type OperationTypeDefinitionNode = {
 *      +kind: 'OperationTypeDefinition',
 *      +loc?: Location,
 *      +operation: OperationTypeNode,
 *      +type: NamedTypeNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L426
 */
class OperationTypeDefinitionNode extends DefinitionNode
{
    /**
     * @var string
     */
    public string $operation;

    /**
     * @var NamedTypeNode
     */
    public NamedTypeNode $type;

    /**
     * OperationTypeDefinitionNode constructor.
     *
     * @param string $operation
     * @param NamedTypeNode $type
     */
    public function __construct(string $operation, NamedTypeNode $type)
    {
        $this->operation = $operation;
        $this->type = $type;
    }
}
