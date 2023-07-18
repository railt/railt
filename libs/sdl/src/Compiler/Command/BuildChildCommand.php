<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Exception\InternalErrorException;
use Railt\SDL\Node\Statement\Statement;
use Railt\SDL\Node\Statement\Type\ListTypeNode;
use Railt\SDL\Node\Statement\Type\NamedTypeNode;
use Railt\SDL\Node\Statement\Type\NonNullTypeNode;
use Railt\SDL\Node\Statement\Type\TypeNode;
use Railt\TypeSystem\DefinitionInterface;
use Railt\TypeSystem\InputTypeInterface;
use Railt\TypeSystem\ListType;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\OutputTypeInterface;
use Railt\TypeSystem\TypeInterface;
use Railt\TypeSystem\WrappingTypeInterface;

/**
 * @template TStatementNode of Statement
 * @template TDefinition of DefinitionInterface
 *
 * @template-extends BuildCommand<TStatementNode, TDefinition>
 */
abstract class BuildChildCommand extends BuildCommand implements BuildChildCommandInterface
{
    /**
     * Build generic type from definition, like:
     * ```
     *  return new NonNullType(
     *    new ListType(
     *      $this->getType('Some'),
     *    )
     *  )
     * ```
     *
     * From `[Some]!` type defintion.
     */
    protected function getTypeReference(TypeNode $node, DefinitionInterface $from = null): TypeInterface
    {
        if ($node instanceof NonNullTypeNode) {
            return new NonNullType($this->getTypeReference($node->type, $from));
        }

        if ($node instanceof ListTypeNode) {
            return new ListType($this->getTypeReference($node->type, $from));
        }

        if ($node instanceof NamedTypeNode) {
            return $this->ctx->getType($node->name->value, $node->name, $from);
        }

        throw InternalErrorException::fromUnprocessableNode($node);
    }

    protected function isInputType(TypeInterface $type): bool
    {
        if (!$type instanceof InputTypeInterface) {
            return false;
        }

        if ($type instanceof WrappingTypeInterface) {
            return $this->isInputType($type->getOfType());
        }

        return true;
    }

    protected function isOutputType(TypeInterface $type): bool
    {
        if (!$type instanceof OutputTypeInterface) {
            return false;
        }

        if ($type instanceof WrappingTypeInterface) {
            return $this->isOutputType($type->getOfType());
        }

        return true;
    }
}
