<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\EnumTypeInterface;
use Railt\SDL\Backend\Context;
use Railt\SDL\Frontend\Ast\Definition\Type\EnumTypeDefinitionNode;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Schema;
use Railt\TypeSystem\Type\EnumType;

/**
 * @property-read EnumTypeDefinitionNode $ast
 * @method EnumTypeDefinitionNode getAst()
 */
class EnumTypeDefinitionContext extends NamedTypeContext
{
    /**
     * EnumTypeDefinitionContext constructor.
     *
     * @param Context $context
     * @param Schema $schema
     * @param EnumTypeDefinitionNode $ast
     */
    public function __construct(Context $context, Schema $schema, EnumTypeDefinitionNode $ast)
    {
        parent::__construct($context, $schema, $ast);
    }

    /**
     * @param array $variables
     * @return EnumTypeInterface
     * @throws TypeUniquenessException
     * @throws \Throwable
     */
    public function resolve(array $variables = []): EnumTypeInterface
    {
        $ast = $this->precompile($this->ast, $variables);

        $enum = new EnumType($ast->name->value, [
            'description' => $this->descriptionOf($ast),
        ]);

        foreach ($ast->values as $value) {
            $enum->addValue($this->buildEnumValueDefinition($value));
        }

        foreach ($ast->directives as $directive) {
            $this->executeDirective($enum, $directive);
        }

        return $enum;
    }
}
