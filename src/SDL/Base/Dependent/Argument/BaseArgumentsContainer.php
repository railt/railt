<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base\Dependent\Argument;

use Railt\SDL\Contracts\Dependent\Argument\HasArguments;
use Railt\SDL\Contracts\Dependent\ArgumentDefinition;

/**
 * Trait BaseArgumentsContainer
 * @mixin HasArguments
 */
trait BaseArgumentsContainer
{
    /**
     * @var array|ArgumentDefinition[]
     */
    protected $arguments = [];

    /**
     * @return iterable|ArgumentDefinition[]
     */
    public function getArguments(): iterable
    {
        return \array_values($this->arguments);
    }

    /**
     * @param ArgumentDefinition $argument
     * @return void
     */
    public function addArgument(ArgumentDefinition $argument): void
    {
        $this->arguments[$argument->getName()] = $argument;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        return \array_key_exists($name, $this->arguments);
    }

    /**
     * @param string $name
     * @return null|ArgumentDefinition
     */
    public function getArgument(string $name): ?ArgumentDefinition
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfArguments(): int
    {
        return \count($this->arguments);
    }

    /**
     * @return int
     */
    public function getNumberOfRequiredArguments(): int
    {
        return (int)\array_reduce($this->arguments, [$this, 'requiredArgumentsCounter'], 0);
    }

    /**
     * @return int
     */
    public function getNumberOfOptionalArguments(): int
    {
        return (int)\array_reduce($this->arguments, [$this, 'optionalArgumentsCounter'], 0);
    }

    /**
     * @param int $carry
     * @param ArgumentDefinition $argument
     * @return int
     */
    private function optionalArgumentsCounter(int $carry, ArgumentDefinition $argument): int
    {
        return $carry + (int)$argument->hasDefaultValue();
    }

    /**
     * @param int $carry
     * @param ArgumentDefinition $argument
     * @return int
     */
    private function requiredArgumentsCounter(int $carry, ArgumentDefinition $argument): int
    {
        return $carry + (int)! $argument->hasDefaultValue();
    }
}
