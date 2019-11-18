<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Executable;

use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Type\NamedTypeNode;
use Railt\SDL\Ast\Value\StringValueNode;
use Railt\SDL\Ast\Generic\TypeImportsCollection;

/**
 * Class ImportNode
 */
abstract class ImportNode extends DefinitionNode
{
    /**
     * @var TypeImportsCollection|NamedTypeNode[]
     */
    public TypeImportsCollection $types;

    /**
     * @var StringValueNode
     */
    public StringValueNode $from;

    /**
     * ImportNode constructor.
     *
     * @param TypeImportsCollection $types
     * @param StringValueNode $from
     */
    public function __construct(TypeImportsCollection $types, StringValueNode $from)
    {
        $this->types = $types;
        $this->from = $from;
    }
}
