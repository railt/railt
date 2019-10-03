<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Executable;

use Railt\Parser\Node\DefinitionNode;

/**
 * Class ExecutableDefinitionNode
 *
 * <code>
 *  export type ExecutableDefinitionNode =
 *      | OperationDefinitionNode
 *      | FragmentDefinitionNode
 *      ;
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L205
 */
abstract class ExecutableDefinitionNode extends DefinitionNode
{
}
