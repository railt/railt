<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Definitions;

use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;

/**
 * Class DirectiveInvocationValidator
 */
class DirectiveInvocationValidator extends BaseDefinitionValidator
{
    /**
     * @param Definition $definition
     * @return bool
     */
    public function match(Definition $definition): bool
    {
        return $definition instanceof DirectiveInvocation;
    }

    /**
     * @param Definition|DirectiveInvocation $definition
     */
    public function validate(Definition $definition): void
    {
        $parent = $definition->getParent();

        if ($parent instanceof ArgumentDefinition) {
            $this->validateArgumentDirective($definition, $parent);
        }
    }

    /**
     * @param DirectiveInvocation $definition
     * @param ArgumentDefinition $arg
     */
    private function validateArgumentDirective(DirectiveInvocation $definition, ArgumentDefinition $arg): void
    {
        $parent = $arg->getParent();

        if ($parent instanceof DirectiveDefinition) {
            $this->validateDirectiveLocatedDirective($definition, $parent);
        }
    }

    /**
     * @param DirectiveInvocation $invoke
     * @param DirectiveDefinition $def
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    private function validateDirectiveLocatedDirective(DirectiveInvocation $invoke, DirectiveDefinition $def): void
    {
        if ($def->getName() === $invoke->getName()) {
            $error = \sprintf('Can not define the %s on %s to itself', $def, $invoke->getParent());
            throw new TypeConflictException($error, $this->getCallStack());
        }
    }
}
