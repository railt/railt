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
use Railt\Parser\Ast\NameNode;
use Railt\Parser\Ast\Type\NamedTypeNode;
use Railt\SDL\Exception\TypeNotFoundException;

/**
 * Class TypeDependenciesLinkerVisitor
 */
class TypeDependenciesLinkerVisitor extends LinkerVisitor
{
    /**
     * @var string
     */
    private const ERROR_NOT_FOUND = 'Type "%s" not found or could not be loaded';

    /**
     * @param NodeInterface $node
     * @return void
     * @throws NotAccessibleException
     * @throws TypeNotFoundException
     * @throws \RuntimeException
     */
    public function leave(NodeInterface $node): void
    {
        if ($node instanceof NamedTypeNode) {
            $this->resolveFromNamedType($node);
        }
    }

    /**
     * @param NamedTypeNode $node
     * @return void
     * @throws TypeNotFoundException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    private function resolveFromNamedType(NamedTypeNode $node): void
    {
        $status = $this->resolve($node->name, $node, function (NameNode $name): bool {
            return $this->document->hasType($name->value);
        });

        if ($status === false) {
            $message = \sprintf(self::ERROR_NOT_FOUND, $node->name->value);

            throw new TypeNotFoundException($message, $node->name);
        }
    }
}
