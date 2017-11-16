<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Uniqueness\Type;

use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Validation\Base\BaseValidator;
use Railt\Compiler\Reflection\Validation\Uniqueness\TypeUniquenessValidator;

/**
 * Class UniqueDefinitionValidator
 */
class UniqueDefinitionValidator extends BaseValidator implements TypeUniquenessValidator
{
    /**
     * @param mixed $container
     * @param TypeDefinition|string $item
     * @return bool
     */
    public function match($container, $item): bool
    {
        return ! \is_iterable($container) && $item instanceof TypeDefinition;
    }

    /**
     * @param null|TypeDefinition $container
     * @param TypeDefinition $definition
     * @return void
     * @throws TypeConflictException
     */
    public function validate($container, TypeDefinition $definition): void
    {
        \assert($container instanceof TypeDefinition || $container === null);

        if (! $this->isEmpty($container) && ! $this->isSameType($container, $definition)) {
            $error = \sprintf(static::REDEFINITION_ERROR, $definition);
            throw new TypeConflictException($error, $this->getCallStack());
        }
    }

    /**
     * @param mixed $container
     * @return bool
     */
    private function isEmpty($container): bool
    {
        return $container === null;
    }

    /**
     * @param mixed $container
     * @param TypeDefinition $definition
     * @return bool
     */
    private function isSameType($container, TypeDefinition $definition): bool
    {
        return $container instanceof TypeDefinition &&
            $container->getUniqueId() === $definition->getUniqueId();
    }
}
