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
use Railt\Reflection\Base\Invocations\Argument\HasPassedArguments;
use Railt\Reflection\Contracts\Invocations\InputInvocation;

/**
 * Class BaseInputInvocation
 */
abstract class BaseInputInvocation extends BaseDependent implements InputInvocation
{
    use HasPassedArguments;

    /**
     * Directive type name
     */
    protected const TYPE_NAME = 'Input';

    /**
     * @return iterable
     */
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->arguments);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->hasPassedArgument($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getPassedArgument($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     * @throws \BadMethodCallException
     */
    public function offsetSet($offset, $value): void
    {
        $error = 'Changing the value of "%s" is not available. Can not change immutable data-set';
        throw new \BadMethodCallException(\sprintf($error, $offset));
    }

    /**
     * @param mixed $offset
     * @return void
     * @throws \BadMethodCallException
     */
    public function offsetUnset($offset): void
    {
        $error = 'Removing the "%s" is not available. Can not change immutable data-set';
        throw new \BadMethodCallException(\sprintf($error, $offset));
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->arguments;
    }
}
