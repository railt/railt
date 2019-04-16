<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Builder\Invocations\Directive;

use Railt\Component\Parser\Ast\NodeInterface;
use Railt\Component\SDL\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;
use Railt\Component\SDL\Reflection\Builder\Invocations\DirectiveInvocationBuilder;

/**
 * Trait DirectivesBuilder
 */
trait DirectivesBuilder
{
    /**
     * @param NodeInterface $ast
     * @return bool
     * @throws \Railt\Component\SDL\Exceptions\TypeConflictException
     */
    protected function compileDirectivesBuilder(NodeInterface $ast): bool
    {
        if ($ast->is('Directive')) {
            /** @var BaseDirectivesContainer|TypeDefinition $this */
            $directive = new DirectiveInvocationBuilder($ast, $this->getDocument(), $this);

            $this->directives[] = [
                $directive->getName() => $directive,
            ];

            return true;
        }

        return false;
    }
}
