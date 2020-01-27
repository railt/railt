<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend;

use Phplrt\Source\Exception\NotAccessibleException;
use Railt\Dumper\Facade;
use Railt\SDL\Exception\TypeErrorException;
use Railt\SDL\Frontend\Ast\Node;
use Railt\TypeSystem\Value\BooleanValue;
use Railt\TypeSystem\Value\FloatValue;
use Railt\TypeSystem\Value\InputObjectValue;
use Railt\TypeSystem\Value\IntValue;
use Railt\TypeSystem\Value\ListValue;
use Railt\TypeSystem\Value\NullValue;
use Railt\TypeSystem\Value\StringValue;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class ValueFactory
 */
class ValueFactory
{
    /**
     * @param mixed $value
     * @param Node|null $context
     * @return ValueInterface
     * @throws TypeErrorException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function make($value, Node $context = null): ValueInterface
    {
        try {
            return $this->create($value);
        } catch (\Throwable $e) {
            throw new TypeErrorException($e->getMessage(), $context, $e);
        }
    }

    /**
     * @param mixed $value
     * @return ValueInterface
     * @throws \InvalidArgumentException
     * @throws \OverflowException
     */
    private function create($value): ValueInterface
    {
        switch (true) {
            case $value instanceof ValueInterface:
                return $value;

            case \is_bool($value):
                return BooleanValue::parse($value);

            case \is_float($value):
                return FloatValue::parse($value);

            case \is_int($value):
                return IntValue::parse($value);

            case $value === null:
                return NullValue::parse($value);

            case \is_string($value):
                return StringValue::parse($value);

            case \is_iterable($value):
                return $this->fromIterator($value);

            default:
                $error = 'Value of type %s can not be converted to GraphQL type';

                throw new \InvalidArgumentException(\sprintf($error, Facade::dump($value)));
        }
    }

    /**
     * @param iterable $iterator
     * @return ValueInterface
     * @throws \InvalidArgumentException
     * @throws \OverflowException
     */
    private function fromIterator(iterable $iterator): ValueInterface
    {
        [$result, $isObject] = [[], false];

        foreach ($iterator as $key => $value) {
            if (\is_string($key)) {
                $isObject = true;
            }

            if ($isObject) {
                $result[$key] = $this->create($value);
            } else {
                $result[] = $this->create($value);
            }
        }

        return $isObject
            ? InputObjectValue::parse($result)
            : ListValue::parse($result);
    }
}
