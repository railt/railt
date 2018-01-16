<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Validation\Definitions;

use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\SDL\Reflection\Validation\Definitions;

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
        $this->validateDirectiveLocation($definition);

        $parent = $definition->getParent();
        if ($parent instanceof ArgumentDefinition) {
            $this->validateArgumentDirective($definition, $parent);
        }
    }

    /**
     * @param DirectiveInvocation $directive
     * @return void
     */
    private function validateDirectiveLocation(DirectiveInvocation $directive): void
    {
        /** @var DirectiveDefinition $definition */
        $definition = $directive->getTypeDefinition();

        if (! $definition->isAllowedFor($directive->getParent())) {
            $error = 'Directive ' . (string)$directive . ' not available for define on ' . (string)$definition;
            throw new TypeConflictException($error, $this->getCallStack());
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
     * @throws \Railt\SDL\Exceptions\TypeConflictException
     */
    private function validateDirectiveLocatedDirective(DirectiveInvocation $invoke, DirectiveDefinition $def): void
    {
        if ($def->getName() === $invoke->getName()) {
            $error = \sprintf('Can not define the %s on %s to itself', $def, $invoke->getParent());
            throw new TypeConflictException($error, $this->getCallStack());
        }
    }
}
