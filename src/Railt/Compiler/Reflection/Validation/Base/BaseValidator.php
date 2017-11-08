<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Base;

use Railt\Compiler\Exceptions\SchemaException;
use Railt\Compiler\Kernel\CallStack;
use Railt\Compiler\Reflection\Support;
use Railt\Compiler\Reflection\Validation\Validator;

/**
 * Class BaseValidator
 */
abstract class BaseValidator implements ValidatorInterface
{
    use Support;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var string
     */
    private $name;

    /**
     * BaseValidator constructor.
     * @param Validator $factory
     * @param CallStack $stack
     * @param null|string $name
     */
    public function __construct(Validator $factory, CallStack $stack, ?string $name)
    {
        $this->validator = $factory;
        $this->stack = $stack;
        $this->name = $name ?? static::class;
    }

    /**
     * @return string
     */
    public function getGroupName(): string
    {
        return $this->name;
    }

    /**
     * @param string $group
     * @return ValidatorInterface
     * @throws \OutOfBoundsException
     */
    public function getValidator(string $group): ValidatorInterface
    {
        return $this->validator->group($group);
    }

    /**
     * @return CallStack
     */
    public function getCallStack(): CallStack
    {
        return $this->stack;
    }

    /**
     * @param string $exception
     * @param string $message
     * @param array ...$args
     * @return void
     */
    public function throw(string $exception, string $message, ...$args): void
    {
        $message = \sprintf($message, ...$args);

        if (\is_subclass_of($exception, SchemaException::class)) {
            throw new $exception($message, $this->stack);
        }

        throw new $exception($message);
    }
}
