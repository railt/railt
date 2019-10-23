<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Tests\Feature\FeatureContext\Support;

/**
 * Trait TypeCastTrait
 */
trait TypeCastTrait
{
    /**
     * @param string $key
     * @param string $value
     * @return mixed
     */
    protected function cast(string $key, string $value)
    {
        switch (\strtolower(\trim($key))) {
            case 'int':
            case 'digit':
            case 'integer':
                return (int)$value;

            case 'string':
            case 'text':
            case 'word':
            case 'char':
            case 'character':
                return $value;

            case 'bool':
            case 'boolean':
                return \strtolower(\trim($value)) === 'true';

            case 'float':
            case 'real':
            case 'double':
            case 'number':
                return (float)$value;

            case 'json':
            case 'object':
            case 'array':
                return \json_decode($value, \JSON_THROW_ON_ERROR);

            default:
                throw new \InvalidArgumentException('Invalid type ' . $value);
        }
    }
}
