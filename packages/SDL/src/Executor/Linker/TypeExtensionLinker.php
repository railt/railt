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
use Railt\SDL\Exception\TypeNotFoundException;
use Phplrt\Source\Exception\NotAccessibleException;

/**
 * Class TypeExtensionLinker
 */
abstract class TypeExtensionLinker extends TypeLinker
{
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

        if (! $this->loaded($node, $this->getLinkerType(), $node->name->value)) {
            throw new TypeNotFoundException(\sprintf($this->getErrorMessage(), $node->name->value), $node);
        }
    }

    /**
     * @param NodeInterface $node
     * @return bool
     */
    abstract protected function match(NodeInterface $node): bool;

    /**
     * @return int
     */
    abstract protected function getLinkerType(): int;

    /**
     * @return string
     */
    abstract protected function getErrorMessage(): string;
}
