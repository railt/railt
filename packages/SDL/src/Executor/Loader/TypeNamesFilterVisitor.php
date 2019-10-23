<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Loader;

use Railt\Parser\Ast\Node;
use Railt\Parser\Ast\TypeSystem\Definition\DirectiveDefinitionNode;
use Railt\Parser\Ast\TypeSystem\TypeDefinitionNode;

/**
 * Class TypeNamesFilterVisitor
 */
class TypeNamesFilterVisitor extends FilterVisitor
{
    /**
     * TypeNamesFilterVisitor constructor.
     *
     * @param array|null $names
     */
    public function __construct(array $names = null)
    {
        parent::__construct(function (Node $node) use ($names): bool {
            if ($names === null) {
                return true;
            }

            /** @var TypeDefinitionNode|DirectiveDefinitionNode $node */
            if ($this->isNamedDefinition($node)) {
                return \in_array($node->name->value, $names, true);
            }

            return false;
        });
    }

    /**
     * @param Node $node
     * @return bool
     */
    private function isNamedDefinition(Node $node): bool
    {
        return $node instanceof TypeDefinitionNode || $node instanceof DirectiveDefinitionNode;
    }
}
