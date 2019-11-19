<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor;

use Railt\SDL\Ast\Executable\DirectiveNode;
use Railt\SDL\Ast\Definition\TypeDefinitionNode;
use Railt\SDL\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Ast\Definition\DirectiveDefinitionNode;

/**
 * Class Registry
 */
class Registry
{
    /**
     * @var array|TypeDefinitionNode[]
     */
    public array $typeMap = [];

    /**
     * @var array|DirectiveDefinitionNode[]
     */
    public array $directives = [];

    /**
     * @var SchemaDefinitionNode|null
     */
    public ?SchemaDefinitionNode $schema = null;
}
