<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\ScalarTypeInterface;
use Railt\SDL\Backend\Context;
use Railt\SDL\Frontend\Ast\Definition\Type\ScalarTypeDefinitionNode;
use Railt\TypeSystem\Schema;
use Railt\TypeSystem\Type\ScalarType;

/**
 * @property-read ScalarTypeDefinitionNode $ast
 * @method ScalarTypeDefinitionNode getAst()
 */
class ScalarTypeDefinitionContext extends NamedTypeContext
{
    /**
     * ScalarTypeDefinitionContext constructor.
     *
     * @param Context $context
     * @param Schema $schema
     * @param ScalarTypeDefinitionNode $ast
     */
    public function __construct(Context $context, Schema $schema, ScalarTypeDefinitionNode $ast)
    {
        parent::__construct($context, $schema, $ast);
    }

    /**
     * @param array $variables
     * @return ScalarTypeInterface
     * @throws \Throwable
     */
    public function resolve(array $variables = []): ScalarTypeInterface
    {
        $ast = $this->precompile($this->ast, $variables);

        $scalar = new ScalarType($ast->name->value, [
            'description' => $this->descriptionOf($ast),
        ]);

        foreach ($ast->directives as $directive) {
            $this->executeDirective($scalar, $directive);
        }

        return $scalar;
    }
}
