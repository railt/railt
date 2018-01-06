<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Validation\Definitions;

use Railt\GraphQL\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Dependent\Argument\HasArguments;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Invocations\Argument\HasPassedArguments;
use Railt\Reflection\Contracts\Invocations\InputInvocation;

/**
 * Class InputInvocationValidator
 */
class PassedArgumentsValidator extends BaseDefinitionValidator
{
    /**
     * @param Definition $definition
     * @return bool
     */
    public function match(Definition $definition): bool
    {
        return $definition instanceof HasPassedArguments;
    }

    /**
     * @param Definition|InputInvocation $invocation
     * @return void
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    public function validate(Definition $invocation): void
    {
        $this->getCallStack()->push($invocation);

        /** @var HasArguments $container */
        $container = $invocation->getTypeDefinition();

        foreach ($invocation->getPassedArguments() as $argument => $value) {
            $this->validateArgumentExisting($container, $argument);
        }

        foreach ($container->getArguments() as $argument) {
            $this->validateMissingArgument($invocation, $argument);
        }

        $this->getCallStack()->pop();
    }

    /**
     * @param HasPassedArguments $invocation
     * @param ArgumentDefinition $argument
     * @return void
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    private function validateMissingArgument(HasPassedArguments $invocation, ArgumentDefinition $argument): void
    {
        if (! $invocation->hasPassedArgument($argument->getName())) {
            $error = \sprintf('Required argument "%s" of %s not specified', $argument, $argument->getParent());

            throw new TypeConflictException($error, $this->getCallStack());
        }
    }

    /**
     * @param HasArguments $container
     * @param string $argument
     * @return void
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    private function validateArgumentExisting(HasArguments $container, string $argument): void
    {
        if (! $container->hasArgument($argument)) {
            $error = \sprintf('In the %s there is no specified argument "%s"', $container, $argument);

            throw new TypeConflictException($error, $this->getCallStack());
        }
    }
}
