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
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Invocations\Directive\HasDirectives;
use Railt\Compiler\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\Compiler\Exceptions\TypeConflictException;
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
        if ($this instanceof HasDirectives && $ast->getId() === '#Directive') {
            /** @var BaseDirectivesContainer|Nameable $this */
            $relation = new DirectiveInvocationBuilder($ast, $this->getDocument(), $this);

            $this->checkDirectiveLocation($relation);

            $this->directives[$relation->getName()] = $relation;

            $this->checkTheDeprecationDirective($relation);

            return true;
        }

        return false;
    }

    /**
     * @param DirectiveInvocation $relation
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    private function checkDirectiveLocation(DirectiveInvocation $relation): void
    {
        $directive = $relation->getDefinition();

        if ($this instanceof Definition && ! $directive->isAllowedFor($this)) {
            $error = 'The usage of the @%s directive together with type %s<%s> is not allowed by the ' .
                'locations of this directive (%s).';

            $error = \sprintf(
                $error,
                $directive->getName(),
                $this->getTypeName(),
                $this->getName(),
                \implode(', ', $directive->getLocations())
            );

            throw new TypeConflictException($error);
        }
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
