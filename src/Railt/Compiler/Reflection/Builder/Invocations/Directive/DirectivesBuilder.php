<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Invocations\Directive;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Builder\Invocations\DirectiveInvocationBuilder;
use Railt\Reflection\Base\Behavior\BaseDeprecations;
use Railt\Reflection\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Reflection\Contracts\Behavior\Deprecatable;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Reflection\Standard\Directives\Deprecation;

/**
 * Trait DirectivesBuilder
 */
trait DirectivesBuilder
{
    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    protected function compileDirectivesBuilder(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Directive') {
            /** @var BaseDirectivesContainer|TypeDefinition $this */
            $directive = new DirectiveInvocationBuilder($ast, $this->getDocument(), $this);

            $this->directives = $this->unique($this->directives, $directive);

            $this->checkTheDeprecationDirective($directive);

            return true;
        }

        return false;
    }

    /**
     * @param DirectiveInvocation $directive
     * @return void
     */
    private function checkTheDeprecationDirective(DirectiveInvocation $directive): void
    {
        if ($this instanceof Deprecatable && $directive->getName() === Deprecation::DIRECTIVE_TYPE_NAME) {
            /** @var BaseDeprecations|Deprecatable $this */
            $this->deprecationReason = $directive->hasPassedArgument(Deprecation::REASON_ARGUMENT)
                ? $this->getDeprecationReasonValue($directive)
                : $this->getDeprecationReasonDefaultValue($directive);
        }
    }

    /**
     * @param DirectiveInvocation $directive
     * @return string
     */
    protected function getDeprecationReasonValue(DirectiveInvocation $directive): string
    {
        return (string)$directive->getPassedArgument(Deprecation::REASON_ARGUMENT);
    }

    /**
     * @param DirectiveInvocation $directive
     * @return string
     */
    protected function getDeprecationReasonDefaultValue(DirectiveInvocation $directive): string
    {
        /** @var DirectiveDefinition $definition */
        $definition = $directive->getTypeDefinition();

        return $definition
            ->getArgument(Deprecation::REASON_ARGUMENT)
            ->getDefaultValue();
    }
}
