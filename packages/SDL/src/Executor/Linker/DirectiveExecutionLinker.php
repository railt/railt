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
use Phplrt\Visitor\Traverser;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Executable\DirectiveNode;
use Railt\SDL\Exception\TypeNotFoundException;
use Railt\SDL\Linker\LinkerInterface;

/**
 * Class DirectiveExecutionLinker
 */
class DirectiveExecutionLinker extends TypeLinker
{
    /**
     * @var string
     */
    private const ERROR_DIRECTIVE_NOT_FOUND = 'Directive "@%s" not found and could not be loaded';

    /**
     * @param NodeInterface|DirectiveNode $node
     * @return mixed|void|null
     * @throws TypeNotFoundException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function enter(NodeInterface $node)
    {
        if (! $node instanceof DirectiveNode) {
            return;
        }

        if (! $this->loaded($node, LinkerInterface::LINK_DIRECTIVE, $node->name->value)) {
            $message = \sprintf(self::ERROR_DIRECTIVE_NOT_FOUND, $node->name->value);
            throw new TypeNotFoundException($message, $node);
        }

        return Traverser::DONT_TRAVERSE_CHILDREN;
    }

    /**
     * @param DefinitionNode|DirectiveNode $directive
     * @return bool
     */
    protected function exists(DefinitionNode $directive): bool
    {
        return $this->registry->directives->containsKey($directive->name->value) ||
            $this->document->directives->containsKey($directive->name->value);
    }
}
