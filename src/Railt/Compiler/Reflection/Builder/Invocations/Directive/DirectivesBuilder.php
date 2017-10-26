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
use Railt\Compiler\Reflection\Base\Behavior\BaseDeprecations;
use Railt\Compiler\Reflection\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Compiler\Reflection\Builder\Invocations\DirectiveInvocationBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Contracts\Behavior\Deprecatable;
use Railt\Compiler\Reflection\Contracts\Behavior\Nameable;
use Railt\Compiler\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Compiler\Reflection\Standard\Directives\Deprecation;

/**
 * Trait DirectivesBuilder
 *
 * @mixin Compiler
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
            /** @var BaseDirectivesContainer|Nameable $this */
            $directive = new DirectiveInvocationBuilder($ast, $this->getDocument(), $this);

            $this->directives = $this->getValidator()->uniqueDefinitions($this->directives, $directive);

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
    private function getDeprecationReasonValue(DirectiveInvocation $directive): string
    {
        return (string)$directive
            ->getPassedArgument(Deprecation::REASON_ARGUMENT)
            ->getPassedValue();
    }

    /**
     * @param DirectiveInvocation $directive
     * @return string
     */
    private function getDeprecationReasonDefaultValue(DirectiveInvocation $directive): string
    {
        return $directive->getDefinition()
            ->getArgument(Deprecation::REASON_ARGUMENT)
            ->getDefaultValue();
    }
}
