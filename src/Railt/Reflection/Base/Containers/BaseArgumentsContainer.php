<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Containers;

use Railt\Reflection\Contracts\Containers\HasArguments;
use Railt\Reflection\Contracts\Types\ArgumentType;

/**
 * Trait BaseArgumentsContainer
 * @mixin HasArguments
 */
trait BaseArgumentsContainer
{
    /**
     * @var array|ArgumentType[]
     */
    protected $arguments = [];

    /**
     * @return iterable|ArgumentType[]
     */
    public function getArguments(): iterable
    {
        return \array_values($this->resolve()->arguments);
    }

    /**
     * @param ArgumentType $argument
     * @return void
     */
    public function addArgument(ArgumentType $argument): void
    {
        $this->arguments[$argument->getName()] = $argument;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        return \array_key_exists($name, $this->resolve()->arguments);
    }

    /**
     * @param string $name
     * @return null|ArgumentType
     */
    public function getArgument(string $name): ?ArgumentType
    {
        return $this->resolve()->arguments[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfArguments(): int
    {
        return \count($this->resolve()->arguments);
    }

    /**
     * @return int
     */
    public function getNumberOfRequiredArguments(): int
    {
        return (int)\array_reduce($this->resolve()->arguments, [$this, 'requiredArgumentsCounter'], 0);
    }

    /**
     * @return int
     */
    public function getNumberOfOptionalArguments(): int
    {
        return (int)\array_reduce($this->resolve()->arguments, [$this, 'optionalArgumentsCounter'], 0);
    }

    /**
     * @param int $carry
     * @param ArgumentType $argument
     * @return int
     */
    private function optionalArgumentsCounter(int $carry, ArgumentType $argument): int
    {
        return $carry + (int)$argument->hasDefaultValue();
    }

    /**
     * @param int $carry
     * @param ArgumentType $argument
     * @return int
     */
    private function requiredArgumentsCounter(int $carry, ArgumentType $argument): int
    {
        return $carry + (int)! $argument->hasDefaultValue();
    }
}
