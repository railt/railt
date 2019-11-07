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
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Extension\InterfaceTypeExtensionNode;
use Railt\SDL\Ast\Type\NamedTypeNode;
use Railt\SDL\Linker\LinkerInterface;

/**
 * Class InterfaceTypeExtensionLinker
 */
class InterfaceTypeExtensionLinker extends TypeExtensionLinker
{
    /**
     * {@inheritDoc}
     */
    protected function match(NodeInterface $node): bool
    {
        return $node instanceof InterfaceTypeExtensionNode;
    }

    /**
     * {@inheritDoc}
     */
    protected function getLinkerType(): int
    {
        return LinkerInterface::LINK_INTERFACE_TYPE;
    }

    /**
     * {@inheritDoc}
     */
    protected function getErrorMessage(): string
    {
        return 'Interface type "%s" not found and could not be loaded';
    }

    /**
     * @param DefinitionNode|NamedTypeNode $type
     * @return bool
     */
    protected function exists(DefinitionNode $type): bool
    {
        return $this->registry->typeMap->containsKey($type->name->value) ||
            $this->document->typeMap->containsKey($type->name->value);
    }
}
