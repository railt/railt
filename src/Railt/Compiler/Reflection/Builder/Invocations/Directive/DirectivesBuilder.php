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

            return true;
        }

        return false;
    }
}
