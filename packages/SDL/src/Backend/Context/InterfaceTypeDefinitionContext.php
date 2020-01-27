<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use Railt\SDL\Backend\Context;
use Railt\SDL\Frontend\Ast\Definition\Type\InterfaceTypeDefinitionNode;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Schema;
use Railt\TypeSystem\Type\InterfaceType;

/**
 * @property-read InterfaceTypeDefinitionNode $ast
 * @method InterfaceTypeDefinitionNode getAst()
 */
class InterfaceTypeDefinitionContext extends NamedTypeContext
{
    /**
     * InterfaceTypeDefinitionContext constructor.
     *
     * @param Context $context
     * @param Schema $schema
     * @param InterfaceTypeDefinitionNode $ast
     */
    public function __construct(Context $context, Schema $schema, InterfaceTypeDefinitionNode $ast)
    {
        parent::__construct($context,  $schema, $ast);
    }

    /**
     * @param array $variables
     * @return InterfaceTypeInterface
     * @throws TypeUniquenessException
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    public function resolve(array $variables = []): InterfaceTypeInterface
    {
        $ast = $this->precompile($this->ast, $variables);
        
        $interface = new InterfaceType($ast->name->value, [
            'description' => $this->descriptionOf($ast),
        ]);

        foreach ($ast->interfaces as $impl) {
            $interface->addInterface($this->ref($impl->interface));
        }

        foreach ($ast->fields as $field) {
            $interface->addField($this->buildFieldDefinition($field));
        }

        foreach ($ast->directives as $directive) {
            $this->executeDirective($interface, $directive);
        }

        return $interface;
    }
}
