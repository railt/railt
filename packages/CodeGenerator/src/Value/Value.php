<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator\Value;

use Railt\CodeGenerator\GeneratorInterface;
use Railt\Config\RepositoryInterface;
use Railt\TypeSystem\EnumValue;
use Railt\TypeSystem\Value\BooleanValue;
use Railt\TypeSystem\Value\FloatValue;
use Railt\TypeSystem\Value\InputObjectValue;
use Railt\TypeSystem\Value\IntValue;
use Railt\TypeSystem\Value\ListValue;
use Railt\TypeSystem\Value\NullValue;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class Value
 */
class Value
{
    /**
     * @param ValueInterface|GeneratorInterface $value
     * @param array|RepositoryInterface $config
     * @return GeneratorInterface
     */
    public static function resolve($value, $config = []): GeneratorInterface
    {
        switch (true) {
            case $value instanceof GeneratorInterface:
                return $value;

            case $value instanceof BooleanValue:
                return new BooleanValueGenerator($value, $config);

            case $value instanceof EnumValue:
                return new EnumValueGenerator($value, $config);

            case $value instanceof FloatValue:
                return new FloatValueGenerator($value, $config);

            case $value instanceof IntValue:
                return new IntValueGenerator($value, $config);

            case  $value instanceof NullValue:
                return new NullValueGenerator();

            case $value instanceof ListValue:
                return new ListValueGenerator($value, $config);

            case $value instanceof InputObjectValue:
                return new InputObjectValueGenerator($value, $config);

            default:
                return new CustomValueGenerator($value, $config);
        }
    }
}
