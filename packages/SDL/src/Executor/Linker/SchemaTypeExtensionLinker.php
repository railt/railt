<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Linker;

use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Extension\SchemaExtensionNode;
use Railt\SDL\Ast\Type\NamedTypeNode;
use Railt\SDL\Exception\TypeNotFoundException;
use Railt\SDL\Linker\LinkerInterface;

/**
 * Class SchemaTypeExtensionLinker
 */
class SchemaTypeExtensionLinker extends TypeLinker
{
    /**
     * {@inheritDoc}
     */
    protected function match(NodeInterface $node): bool
    {
        return $node instanceof SchemaExtensionNode;
    }

    /**
     * {@inheritDoc}
     */
    protected function getLinkerType(): int
    {
        return LinkerInterface::LINK_SCHEMA;
    }

    /**
     * {@inheritDoc}
     */
    protected function getErrorMessage(): string
    {
        return 'Schema not found and could not be loaded';
    }

    /**
     * @param mixed $node
     * @return void
     * @throws TypeNotFoundException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function enter(NodeInterface $node): void
    {
        if (! $this->match($node)) {
            return;
        }

        if (! $this->loaded($node, $this->getLinkerType(), null)) {
            throw new TypeNotFoundException($this->getErrorMessage(), $node);
        }
    }

    /**
     * @param DefinitionNode|NamedTypeNode $type
     * @return bool
     */
    protected function exists(DefinitionNode $type): bool
    {
        return $this->registry->schema || $this->document->getSchema();
    }
}
