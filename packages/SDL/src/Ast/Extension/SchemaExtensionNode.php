<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Extension;

use Railt\SDL\Ast\Value\StringValueNode;
use Railt\SDL\Ast\Generic\DirectiveCollection;
use Railt\SDL\Ast\Generic\OperationTypeDefinitionCollection;

/**
 * Class SchemaExtensionNode
 *
 * <code>
 *  export type SchemaExtensionNode = {
 *      readonly kind: 'SchemaExtension';
 *      readonly loc?: Location;
 *      readonly directives?: ReadonlyArray<DirectiveNode>;
 *      readonly operationTypes?: ReadonlyArray<OperationTypeDefinitionNode>;
 *  }
 * </code>
 */
class SchemaExtensionNode extends TypeSystemExtensionNode
{
    /**
     * @var StringValueNode|null
     */
    public ?StringValueNode $description = null;

    /**
     * @var DirectiveCollection|null
     */
    public ?DirectiveCollection $directives = null;

    /**
     * @var OperationTypeDefinitionCollection|null
     */
    public ?OperationTypeDefinitionCollection $operationTypes = null;
}
