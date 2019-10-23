<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Feature\Support;

/**
 * Trait TypeCastTrait
 */
trait TypeCastTrait
{
    /**
     * @param string $key
     * @param string|null $value
     * @return mixed
     */
    protected function cast(string $key, string $value = null)
    {
        switch (\strtolower(\trim($key))) {
            case 'null':
            case 'empty':
            case 'nothing':
                return null;

            case 'int':
            case 'digit':
            case 'integer':
                return (int)$value;

            case 'string':
            case 'text':
            case 'word':
            case 'char':
            case 'character':
                return (string)$value;

            case 'bool':
            case 'boolean':
                return \strtolower(\trim((string)$value)) === 'true';

            case 'float':
            case 'real':
            case 'double':
            case 'number':
                return (float)$value;

            case 'object':
            case 'json':
                return \json_decode((string)$value, false, 512, \JSON_THROW_ON_ERROR);

            case 'array':
                return \json_decode((string)$value, true, 512, \JSON_THROW_ON_ERROR);

            default:
                throw new \InvalidArgumentException('Invalid type ' . $value);
        }
    }
}
