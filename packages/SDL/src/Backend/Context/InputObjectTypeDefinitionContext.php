<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\InputObjectTypeInterface;
use Railt\SDL\Backend\Context;
use Railt\SDL\Frontend\Ast\Definition\Type\InputObjectTypeDefinitionNode;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Schema;
use Railt\TypeSystem\Type\InputObjectType;

/**
 * @property-read InputObjectTypeDefinitionNode $ast
 * @method InputObjectTypeDefinitionNode getAst()
 */
class InputObjectTypeDefinitionContext extends NamedTypeContext
{
    /**
     * InputObjectTypeDefinitionContext constructor.
     *
     * @param Context $context
     * @param Schema $schema
     * @param InputObjectTypeDefinitionNode $ast
     */
    public function __construct(Context $context, Schema $schema, InputObjectTypeDefinitionNode $ast)
    {
        parent::__construct($context, $schema, $ast);
    }

    /**
     * @param array $variables
     * @return InputObjectTypeInterface
     * @throws TypeUniquenessException
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    public function resolve(array $variables = []): InputObjectTypeInterface
    {
        $ast = $this->precompile($this->ast, $variables);

        $input = new InputObjectType($ast->name->value, [
            'description' => $this->descriptionOf($ast),
        ]);

        foreach ($ast->fields as $field) {
            $input->addField($this->buildInputFieldDefinition($field));
        }

        foreach ($ast->directives as $directive) {
            $this->executeDirective($input, $directive);
        }

        return $input;
    }
}
