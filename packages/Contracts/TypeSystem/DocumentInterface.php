<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\TypeSystem;

use Railt\Parser\Ast\DefinitionNode;
use Railt\Parser\Ast\TypeSystem\Definition\DirectiveDefinitionNode;
use Railt\Parser\Ast\TypeSystem\Definition\SchemaDefinitionNode;
use Railt\Parser\Ast\TypeSystem\TypeDefinitionNode;

/**
 * Interface DocumentInterface
 */
interface DocumentInterface
{
    /**
     * @return array|SchemaDefinitionNode[]
     */
    public function schemas(): array;

    /**
     * @return array|TypeDefinitionNode[]
     */
    public function types(): array;

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool;

    /**
     * @return array|DirectiveDefinitionNode[]
     */
    public function directives(): array;

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool;

    /**
     * @return array|DefinitionNode[]
     */
    public function executions(): array;
}
