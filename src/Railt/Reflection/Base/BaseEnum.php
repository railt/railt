<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Contracts\Types\Enum\Value;
use Railt\Reflection\Contracts\Types\EnumType;

/**
 * Class BaseEnum
 */
abstract class BaseEnum extends BaseNamedType implements EnumType
{
    /**
     * @var array|Value[]
     */
    protected $values = [];

    /**
     * @return iterable|Value[]
     */
    public function getValues(): iterable
    {
        return \array_values($this->compiled()->values);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name): bool
    {
        return \array_key_exists($name, $this->compiled()->values);
    }

    /**
     * @param string $name
     * @return null|Value
     */
    public function getValue(string $name): ?Value
    {
        return $this->compiled()->values[$name] ?? null;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Enum';
    }
}
