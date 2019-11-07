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
use Railt\SDL\Ast\Type\NamedTypeNode;
use Railt\SDL\Exception\TypeNotFoundException;
use Railt\SDL\Linker\LinkerInterface;

/**
 * Class NamedTypeLinker
 */
class NamedTypeLinker extends TypeLinker
{
    /**
     * @var string
     */
    private const ERROR_TYPE_NOT_FOUND = 'Type "%s" not found and could not be loaded';

    /**
     * @param NodeInterface|NamedTypeNode $node
     * @return void
     * @throws TypeNotFoundException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function enter(NodeInterface $node): void
    {
        if (! $node instanceof NamedTypeNode) {
            return;
        }

        if (! $this->loaded($node, LinkerInterface::LINK_TYPE, $node->name->value)) {
            $message = \sprintf(self::ERROR_TYPE_NOT_FOUND, $node->name->value);
            throw new TypeNotFoundException($message, $node);
        }
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
