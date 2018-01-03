<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Uniqueness\Scalar;

use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Compiler\Reflection\Validation\Base\BaseValidator;
use Railt\Compiler\Reflection\Validation\Uniqueness\ScalarUniquenessValidator;

/**
 * Class UniqueValueValidator
 */
class UniqueValueValidator extends BaseValidator implements ScalarUniquenessValidator
{
    /**
     * @param array $container
     * @param string $item
     * @return bool
     */
    public function match($container, $item): bool
    {
        return \is_array($container) && \is_string($item);
    }

    /**
     * @param array $container
     * @param string $item
     * @param string $typeName
     * @return void
     * @throws TypeConflictException
     */
    public function validate(array $container, string $item, string $typeName): void
    {
        if (\array_key_exists($item, $container)) {
            $error = \sprintf(static::REDEFINITION_ERROR, $typeName . ' ' . $item);
            throw new TypeConflictException($error, $this->getCallStack());
        }
    }
}
