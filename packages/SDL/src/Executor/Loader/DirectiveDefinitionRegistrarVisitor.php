<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Loader;

use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Document\MutableDocument;
use Railt\SDL\Exception\TypeErrorException;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\Parser\Ast\TypeSystem\Definition\DirectiveDefinitionNode;

/**
 * Class DirectiveDefinitionRegistrarVisitor
 */
class DirectiveDefinitionRegistrarVisitor extends RegistrarVisitor
{
    /**
     * @var string
     */
    private const ERROR_DIRECTIVE_REDEFINITION = 'There can be only one directive named "@%s"';

    /**
     * @param NodeInterface $node
     * @return void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    public function leave(NodeInterface $node): void
    {
        if ($node instanceof DirectiveDefinitionNode) {
            $this->registerDirectiveDefinition($node);
        }
    }

    /**
     * @param DirectiveDefinitionNode $directive
     * @return void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    private function registerDirectiveDefinition(DirectiveDefinitionNode $directive): void
    {
        if ($this->document->hasDirective($directive->name->value)) {
            $message = \sprintf(self::ERROR_DIRECTIVE_REDEFINITION, $directive->name->value);

            throw new TypeErrorException($message, $directive);
        }

        $this->mutate(fn (MutableDocument $document) => $document->withDirective($directive));
    }
}
