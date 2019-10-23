<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast\Executable\Definition;

use Railt\Parser\Ast\NameNode;
use Railt\Parser\Ast\Generic\DirectiveCollection;
use Railt\Parser\Ast\Executable\ExecutableDefinitionNode;
use Railt\Parser\Ast\Generic\VariableDefinitionCollection;

/**
 * Class OperationDefinitionNode
 *
 * <code>
 *  export type OperationDefinitionNode = {
 *      +kind: 'OperationDefinition',
 *      +loc?: Location,
 *      +operation: OperationTypeNode,
 *      +name?: NameNode,
 *      +variableDefinitions?: $ReadOnlyArray<VariableDefinitionNode>,
 *      +directives?: $ReadOnlyArray<DirectiveNode>,
 *      +selectionSet: SelectionSetNode,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L209
 */
class OperationDefinitionNode extends ExecutableDefinitionNode
{
    /**
     * @var string
     */
    public string $operation;

    /**
     * @var SelectionSetNode
     */
    public SelectionSetNode $selectionSet;

    /**
     * @var NameNode|null
     */
    public ?NameNode $name = null;

    /**
     * @var VariableDefinitionCollection|null
     */
    public ?VariableDefinitionCollection $variables = null;

    /**
     * @var DirectiveCollection|null
     */
    public ?DirectiveCollection $directives = null;

    /**
     * OperationDefinitionNode constructor.
     *
     * @param string $operation
     * @param SelectionSetNode $selections
     */
    public function __construct(string $operation, SelectionSetNode $selections)
    {
        $this->operation = $operation;
        $this->selectionSet = $selections;
    }
}
