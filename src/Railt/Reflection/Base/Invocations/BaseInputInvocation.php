<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Invocations;

use Railt\Reflection\Base\Dependent\BaseDependent;
use Railt\Reflection\Contracts\Invocations\ArgumentInvocation;
use Railt\Reflection\Contracts\Invocations\InputInvocation;

/**
 * Class BaseInputInvocation
 */
abstract class BaseInputInvocation extends BaseDependent implements InputInvocation
{
    /**
     * Argument type name
     */
    protected const TYPE_NAME = 'Input';

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @return iterable|ArgumentInvocation[]
     */
    public function getPassedValues(): iterable
    {
        return \array_values($this->values);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasPassedValue(string $name): bool
    {
        return \array_key_exists($name, $this->values);
    }

    /**
     * @param string $name
     * @return null|ArgumentInvocation
     */
    public function getPassedValue(string $name): ?ArgumentInvocation
    {
        return $this->values[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfPassedValues(): int
    {
        return \count($this->values);
    }

    /**
     * @return iterable
     */
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->values);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->hasPassedValue($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getPassedValue($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $error = 'Changing the value of "%s" is not available. Can not change immutable data-set';
        throw new \BadMethodCallException(\sprintf($error, $offset));
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        $error = 'Removing the "%s" is not available. Can not change immutable data-set';
        throw new \BadMethodCallException(\sprintf($error, $offset));
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // Values
            'values',
        ]);
    }
}
