<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Dependent;

use Railt\Reflection\Base\Behavior\BaseTypeIndicator;
use Railt\Reflection\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Reflection\Contracts\Behavior\Inputable;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Dependent\Argument\HasArguments;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class BaseArgument
 */
abstract class BaseArgument extends BaseDependent implements ArgumentDefinition
{
    use BaseTypeIndicator;
    use BaseDirectivesContainer;

    /**
     * Argument type name
     */
    protected const TYPE_NAME = 'Argument';

    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @var bool
     */
    protected $hasDefaultValue = false;

    /**
     * @return HasArguments
     */
    public function getParent(): HasArguments
    {
        return $this->resolve()->parent;
    }

    /**
     * @return Inputable|Definition
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function getType(): Inputable
    {
        if ($this->resolve()->type instanceof Inputable) {
            return $this->type;
        }

        $error = 'Argument "%s" type must be inputable (Input or Scalar) but "%s" given.';
        throw new TypeConflictException(\sprintf($error, $this->getName(), $this->type->getTypeName()));
    }

    /**
     * @return string|float|int|array|bool|null
     */
    public function getDefaultValue()
    {
        switch (true) {
            case $this->resolve()->hasDefaultValue:
                return $this->defaultValue;

            case ! $this->isNonNull():
                return null;

            case $this->isList():
                return [];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->resolve()->hasDefaultValue  // Initialized by any value
            || ! $this->isNonNull()               // Can be null
            || $this->isList();                   // Can be an empty array
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // self class
            'defaultValue',
            'hasDefaultValue',

            // trait AllowsTypeIndication
            'type',
            'isList',
            'isNonNull',
            'isListOfNonNulls',
        ]);
    }
}
