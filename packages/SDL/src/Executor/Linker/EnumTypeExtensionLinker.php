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
use Railt\SDL\Ast\Extension\EnumTypeExtensionNode;
use Railt\SDL\Linker\LinkerInterface;

/**
 * Class EnumTypeExtensionLinker
 */
class EnumTypeExtensionLinker extends TypeExtensionLinker
{
    /**
     * {@inheritDoc}
     */
    protected function match(NodeInterface $node): bool
    {
        return $node instanceof EnumTypeExtensionNode;
    }

    /**
     * {@inheritDoc}
     */
    protected function getLinkerType(): int
    {
        return LinkerInterface::LINK_ENUM_TYPE;
    }

    /**
     * {@inheritDoc}
     */
    protected function getErrorMessage(): string
    {
        return 'Enum type "%s" not found and could not be loaded';
    }
}
