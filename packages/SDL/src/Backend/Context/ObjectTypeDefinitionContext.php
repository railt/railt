<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use Railt\SDL\Backend\Context;
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Schema;
use Railt\TypeSystem\Type\ObjectType;

/**
 * @property-read ObjectTypeDefinitionNode $ast
 * @method ObjectTypeDefinitionNode getAst()
 */
class ObjectTypeDefinitionContext extends NamedTypeContext
{
    /**
     * ObjectTypeDefinitionContext constructor.
     *
     * @param Context $context
     * @param Schema $schema
     * @param ObjectTypeDefinitionNode $ast
     */
    public function __construct(Context $context, Schema $schema, ObjectTypeDefinitionNode $ast)
    {
        parent::__construct($context, $schema, $ast);
    }

    /**
     * @param array $variables
     * @return ObjectTypeInterface
     * @throws TypeUniquenessException
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    public function resolve(array $variables = []): ObjectTypeInterface
    {
        $ast = $this->precompile($this->ast, $variables);

        $object = new ObjectType($ast->name->value, [
            'description' => $this->descriptionOf($ast),
        ]);

        foreach ($ast->interfaces as $impl) {
            $object->addInterface($this->ref($impl->interface));
        }

        foreach ($ast->fields as $field) {
            $object->addField($this->buildFieldDefinition($field));
        }

        foreach ($ast->directives as $directive) {
            $this->executeDirective($object, $directive);
        }

        return $object;
    }
}
