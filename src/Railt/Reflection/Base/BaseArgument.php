<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Base\Behavior\BaseChild;
use Railt\Reflection\Base\Behavior\BaseTypeIndicator;
use Railt\Reflection\Contracts\Behavior\Inputable;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class BaseArgument
 */
abstract class BaseArgument extends BaseNamedType implements ArgumentType
{
    use BaseChild;
    use BaseTypeIndicator;

    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @var bool
     */
    protected $hasDefaultValue = false;

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Argument';
    }

    /**
     * @return NamedTypeInterface
     * @throws TypeConflictException
     */
    public function getType(): NamedTypeInterface
    {
        if ($this->resolve()->type instanceof Inputable) {
            return $this->type;
        }

        $error = 'Argument "%s" type must be inputable (Input, Union or Scalar) but "%s" given.';
        throw new TypeConflictException(\sprintf($error, $this->getName(), $this->type->getTypeName()));
    }

    /**
     * @return string|float|int|array|bool|null
     */
    public function getDefaultValue()
    {
        if ($this->hasDefaultValue()) {
            return $this->defaultValue;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->resolve()->hasDefaultValue || $this->isNullable();
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'type',
            'isList',
            'isNonNull',
            'isNonNullList',
            'parent',
            'defaultValue',
            'hasDefaultValue',
        ]);
    }
}
