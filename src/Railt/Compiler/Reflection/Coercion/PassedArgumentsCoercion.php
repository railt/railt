<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Coercion;

use Railt\Reflection\Base\Invocations\BaseInputInvocation;
use Railt\Reflection\Contracts\Definitions\InputDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\Argument\HasArguments;
use Railt\Reflection\Contracts\Dependent\DependentDefinition;
use Railt\Reflection\Contracts\Invocations\Argument\HasPassedArguments;
use Railt\Reflection\Contracts\Invocations\InputInvocation;
use Railt\Reflection\Contracts\Invocations\Invocable;

/**
 * Class PassedArgumentsCoercion
 */
class PassedArgumentsCoercion extends BaseTypeCoercion
{
    /**
     * @param TypeDefinition $type
     * @return bool
     */
    public function match(TypeDefinition $type): bool
    {
        return $type instanceof HasPassedArguments;
    }

    /**
     * @param TypeDefinition|HasPassedArguments|Invocable $type
     */
    public function apply(TypeDefinition $type): void
    {
        /** @var HasArguments $container */
        $container = $type->getTypeDefinition();

        if ($container !== null) {
            $this->inferenceDefaultArguments($container, $type);
        }
    }

    /**
     * @param HasArguments $container
     * @param HasPassedArguments $usage
     */
    private function inferenceDefaultArguments(HasArguments $container, HasPassedArguments $usage)
    {
        foreach ($container->getArguments() as $argument) {
            if ($argument->hasDefaultValue() && ! $usage->hasPassedArgument($argument->getName())) {
                $this->set($usage, $argument->getName(), $argument->getDefaultValue());
            }
        }
    }

    /**
     * @param HasPassedArguments $usage
     * @param string $key
     * @param mixed $value
     */
    private function set(HasPassedArguments $usage, string $key, $value)
    {
        $invocation = function(string $key, $value) {
            /** @var BaseInputInvocation $this */
            $this->arguments[$key] = $value;
        };

        $invocation->call($usage, $key, $value);
    }
}
