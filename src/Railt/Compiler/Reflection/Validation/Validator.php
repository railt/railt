<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation;

use Railt\Compiler\Kernel\CallStack;
use Railt\Compiler\Reflection\Validation\Base\Factory;
use Railt\Compiler\Reflection\Validation\Base\ValidatorInterface;

/**
 * Class Validator
 *
 * TODO Add lazy initializing
 */
class Validator
{
    /**
     * A constant list of validator groups
     */
    private const VALIDATOR_GROUPS = [
        /**
         * Type consistency checks
         */
        Definitions::class,

        /**
         * Type Inheritance checks
         */
        Inheritance::class,

        /**
         * Checking the children uniqueness
         */
        Uniqueness::class,
    ];

    /**
     * @var array|Factory[]
     */
    private $groups = [];

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * Validator constructor.
     * @param CallStack $stack
     * @throws \InvalidArgumentException
     */
    public function __construct(CallStack $stack)
    {
        $this->stack = $stack;

        $this->bootDefaults();
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    private function bootDefaults(): void
    {
        foreach (self::VALIDATOR_GROUPS as $factory) {
            $this->add($factory);
        }
    }

    /**
     * @param string $group
     * @return Factory
     * @throws \OutOfBoundsException
     */
    public function group(string $group): Factory
    {
        if (\array_key_exists($group, $this->groups)) {
            return $this->groups[$group];
        }

        $error = 'Validator group %s not exists';
        throw new \OutOfBoundsException(\sprintf($error, $group));
    }

    /**
     * @param string|ValidatorInterface|Factory $factory
     * @param string|null $group
     * @return Factory
     * @throws \InvalidArgumentException
     */
    public function add(string $factory, string $group = null): Factory
    {
        if (! \is_subclass_of($factory, Factory::class)) {
            $error = \sprintf('%s must be instance of %s', $factory, Factory::class);
            throw new \InvalidArgumentException($error);
        }

        /** @var Factory $instance */
        $instance = new $factory($this, $this->stack, $group);

        $this->groups[$instance->getGroupName()] = $instance;

        return $instance;
    }
}
