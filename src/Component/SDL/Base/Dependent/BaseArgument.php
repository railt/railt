<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Base\Dependent;

use Railt\Component\SDL\Base\Behavior\BaseTypeIndicator;
use Railt\Component\SDL\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Component\SDL\Contracts\Dependent\ArgumentDefinition;
use Railt\Component\SDL\Contracts\Type;

/**
 * Class BaseArgument
 */
abstract class BaseArgument extends BaseDependent implements ArgumentDefinition
{
    use BaseTypeIndicator;
    use BaseDirectivesContainer;

    /**
     * Argument type name
     *
     * TODO or input field.
     */
    protected const TYPE_NAME = Type::OBJECT_ARGUMENT;

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
        return $this->defaultValue;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
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

            // directives
            'directives',
        ]);
    }
}
