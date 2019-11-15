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
use Railt\SDL\Ast\Extension\UnionTypeExtensionNode;
use Railt\SDL\Linker\LinkerInterface;

/**
 * Class UnionTypeExtensionLinker
 */
class UnionTypeExtensionLinker extends TypeExtensionLinker
{
    /**
     * {@inheritDoc}
     */
    protected function match(NodeInterface $node): bool
    {
        return $node instanceof UnionTypeExtensionNode;
    }

    /**
     * {@inheritDoc}
     */
    protected function getLinkerType(): int
    {
        return LinkerInterface::LINK_UNION_TYPE;
    }

    /**
     * {@inheritDoc}
     */
    protected function getErrorMessage(): string
    {
        return 'Union type "%s" not found and could not be loaded';
    }
}
