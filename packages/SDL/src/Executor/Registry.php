<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor;

use Ramsey\Collection\Set;
use Ramsey\Collection\Map\TypedMap;
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
     * @var TypedMap|TypeDefinitionNode[]
     */
    public TypedMap $typeMap;

    /**
     * @var TypedMap|DirectiveDefinitionNode[]
     */
    public TypedMap $directives;

    /**
     * @var Set|DirectiveNode[]
     */
    public Set $executions;

    /**
     * @var SchemaDefinitionNode|null
     */
    public ?SchemaDefinitionNode $schema = null;

    /**
     * Registry constructor.
     */
    public function __construct()
    {
        $this->typeMap = new TypedMap('string', TypeDefinitionNode::class);
        $this->directives = new TypedMap('string', DirectiveDefinitionNode::class);
        $this->executions = new Set(DirectiveNode::class);
    }
}
