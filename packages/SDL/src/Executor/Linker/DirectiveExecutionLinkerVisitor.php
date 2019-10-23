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
use Railt\Parser\Ast\Executable\Definition\DirectiveNode;
use Railt\Parser\Ast\NameNode;
use Railt\SDL\Exception\TypeNotFoundException;

/**
 * Class DirectiveExecutionLinkerVisitor
 */
class DirectiveExecutionLinkerVisitor extends LinkerVisitor
{
    /**
     * @var string
     */
    private const ERROR_DIRECTIVE_NOT_FOUND = 'Directive "@%s" not found or could not be loaded';

    /**
     * @param NodeInterface $node
     * @return void
     * @throws TypeNotFoundException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function leave(NodeInterface $node): void
    {
        if ($node instanceof DirectiveNode) {
            $this->resolveFromDirective($node);
        }
    }

    /**
     * @param DirectiveNode $node
     * @return void
     * @throws TypeNotFoundException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    private function resolveFromDirective(DirectiveNode $node): void
    {
        $status = $this->resolve($node->name, $node, function (NameNode $name): bool {
            return $this->document->hasDirective($name->value);
        });

        if ($status === false) {
            $message = \sprintf(self::ERROR_DIRECTIVE_NOT_FOUND, $node->name->value);

            throw new TypeNotFoundException($message, $node);
        }
    }
}
