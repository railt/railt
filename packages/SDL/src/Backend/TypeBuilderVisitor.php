<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend;

use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Visitor\Visitor;
use Railt\SDL\Backend\Context\DirectiveContext;
use Railt\SDL\Backend\Context\EnumTypeDefinitionContext;
use Railt\SDL\Backend\Context\InputObjectTypeDefinitionContext;
use Railt\SDL\Backend\Context\InterfaceTypeDefinitionContext;
use Railt\SDL\Backend\Context\ObjectTypeDefinitionContext;
use Railt\SDL\Backend\Context\ScalarTypeDefinitionContext;
use Railt\SDL\Backend\Context\UnionTypeDefinitionContext;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\EnumTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InputObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\InterfaceTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ObjectTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\ScalarTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\Type\UnionTypeDefinitionNode;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;
use Railt\TypeSystem\Reference\TypeReference;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Railt\TypeSystem\Schema;

/**
 * Class TypeBuilderVisitor
 */
class TypeBuilderVisitor extends Visitor
{
    /**
     * @var Context
     */
    private Context $ctx;

    /**
     * @var Schema
     */
    private Schema $schema;

    /**
     * TypeBuilderVisitor constructor.
     *
     * @param Context $context
     * @param Schema $schema
     */
    public function __construct(Context $context, Schema $schema)
    {
        $this->ctx = $context;
        $this->schema = $schema;
    }

    /**
     * @param NodeInterface $node
     * @return void
     * @throws \Throwable
     */
    public function leave(NodeInterface $node): void
    {
        switch (true) {
            case $node instanceof EnumTypeDefinitionNode:
                $this->ctx->addType(
                    new EnumTypeDefinitionContext($this->ctx, $this->schema, $node)
                );
                break;

            case $node instanceof InputObjectTypeDefinitionNode:
                $this->ctx->addType(
                    new InputObjectTypeDefinitionContext($this->ctx, $this->schema, $node)
                );
                break;

            case $node instanceof InterfaceTypeDefinitionNode:
                $this->ctx->addType(
                    new InterfaceTypeDefinitionContext($this->ctx, $this->schema, $node)
                );
                break;

            case $node instanceof ObjectTypeDefinitionNode:
                $this->ctx->addType(
                    new ObjectTypeDefinitionContext($this->ctx, $this->schema, $node)
                );
                break;

            case $node instanceof ScalarTypeDefinitionNode:
                $this->ctx->addType(
                    new ScalarTypeDefinitionContext($this->ctx, $this->schema, $node)
                );
                break;

            case $node instanceof UnionTypeDefinitionNode:
                $this->ctx->addType(
                    new UnionTypeDefinitionContext($this->ctx, $this->schema, $node)
                );
                break;

            case $node instanceof DirectiveDefinitionNode:
                $this->ctx->addDirective(
                    new DirectiveContext($this->ctx, $this->schema, $node)
                );
                break;

            case $node instanceof SchemaDefinitionNode:
                $this->extendFrom($node);
                break;
        }
    }

    /**
     * @param SchemaDefinitionNode $node
     * @return void
     */
    private function extendFrom(SchemaDefinitionNode $node): void
    {
        foreach ($node->operationTypes as $operation) {
            switch ($operation->operation) {
                case 'query':
                    $this->ctx->setQuery($this->ref($operation->type));
                    break;

                case 'mutation':
                    $this->ctx->setMutation($this->ref($operation->type));
                    break;

                case 'subscription':
                    $this->ctx->setSubscription($this->ref($operation->type));
                    break;
            }
        }

        foreach ($node->directives as $directive) {
            // $this->handleDirective($this->schema, $directive);
        }
    }

    /**
     * @param NamedTypeNode $node
     * @return TypeReferenceInterface
     */
    protected function ref(NamedTypeNode $node): TypeReferenceInterface
    {
        return new TypeReference($this->schema, $node->name->value);
    }
}
