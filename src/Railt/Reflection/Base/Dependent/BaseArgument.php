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
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;

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
     * @return string|float|int|array|bool|null
     */
    public function getDefaultValue()
    {
        switch (true) {
            case $this->hasDefaultValue:
                return $this->defaultValue;

            case ! $this->isNonNull():
                return;

            case $this->isList():
                return [];
        }
    }

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue  // Initialized by any value
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
