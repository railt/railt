<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Invocations\Directive;

use Phplrt\Ast\NodeInterface;
use Railt\SDL\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Reflection\Builder\Invocations\DirectiveInvocationBuilder;

/**
 * Trait DirectivesBuilder
 */
trait DirectivesBuilder
{
    /**
     * @param NodeInterface $ast
     * @return bool
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    protected function compileDirectivesBuilder(NodeInterface $ast): bool
    {
        if ($ast->getName() === 'Directive') {
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
