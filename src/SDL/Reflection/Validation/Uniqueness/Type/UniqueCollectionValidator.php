<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Validation\Uniqueness\Type;

use Railt\SDL\Exceptions\TypeConflictException;
use Railt\SDL\Reflection\Validation\Base\BaseValidator;
use Railt\SDL\Reflection\Validation\Uniqueness\TypeUniquenessValidator;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class UniqueCollectionValidator
 */
class UniqueCollectionValidator extends BaseValidator implements TypeUniquenessValidator
{
    /**
     * @param mixed $container
     * @param TypeDefinition|string $item
     * @return bool
     */
    public function match($container, $item): bool
    {
        return \is_array($container) && $item instanceof TypeDefinition;
    }

    /**
     * @param array $container
     * @param TypeDefinition $definition
     * @return void
     * @throws TypeConflictException
     */
    public function validate($container, TypeDefinition $definition): void
    {
        \assert(\is_array($container));

        if ($this->isExists($container, $definition)) {
            $error = \sprintf(static::REDEFINITION_ERROR, $definition);
            throw new TypeConflictException($error, $this->getCallStack());
        }
    }

    /**
     * @param array $container
     * @param TypeDefinition $type
     * @return bool
     */
    private function isExists(array $container, TypeDefinition $type): bool
    {
        return \array_key_exists($type->getName(), $container);
    }
}
