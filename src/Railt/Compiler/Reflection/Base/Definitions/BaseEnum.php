<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Definitions;

use Railt\Compiler\Reflection\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Compiler\Reflection\Contracts\Definitions\Enum\ValueDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\EnumDefinition;

/**
 * Class BaseEnum
 */
abstract class BaseEnum extends BaseDefinition implements EnumDefinition
{
    use BaseDirectivesContainer;

    /**
     * Enum type name
     */
    protected const TYPE_NAME = 'Enum';

    /**
     * @var array|ValueDefinition[]
     */
    protected $values = [];

    /**
     * @param mixed|string $value
     * @return bool
     */
    public function isCompatible($value): bool
    {
        return \is_string($value) && $this->hasValue($value);
    }

    /**
     * @return iterable|ValueDefinition[]
     */
    public function getValues(): iterable
    {
        return \array_values($this->values);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name): bool
    {
        return \array_key_exists($name, $this->values);
    }

    /**
     * @param string $name
     * @return null|ValueDefinition
     */
    public function getValue(string $name): ?ValueDefinition
    {
        return $this->values[$name] ?? null;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // self class
            'values',

            // trait HasDirectives
            'directives',
        ]);
    }
}
