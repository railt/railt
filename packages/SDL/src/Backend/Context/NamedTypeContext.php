<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\SDL\Backend\Context;
use Railt\SDL\Frontend\Ast\Definition\Type\TypeDefinitionNode;
use Railt\TypeSystem\Schema;

/**
 * @property-read TypeDefinitionNode $ast
 * @method TypeDefinitionNode getAst()
 * @method NamedTypeInterface resolve(array $variables = [])
 */
abstract class NamedTypeContext extends DefinitionContext
{
    /**
     * NamedTypeContext constructor.
     *
     * @param Context $context
     * @param Schema $schema
     * @param TypeDefinitionNode $ast
     */
    public function __construct(Context $context, Schema $schema, TypeDefinitionNode $ast)
    {
        parent::__construct($context, $schema, $ast);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->ast->name->value;
    }
}
