<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Definition;

use Railt\SDL\Ast\Generic\UnionTypesCollection;

/**
 * Class UnionTypeDefinitionNode
 */
class UnionTypeDefinitionNode extends TypeDefinitionNode
{
    /**
     * @var UnionTypesCollection|null
     */
    public ?UnionTypesCollection $types = null;
}
