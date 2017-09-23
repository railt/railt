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
        return \array_values($this->compiled()->arguments);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        return \array_key_exists($name, $this->compiled()->arguments);
    }

    /**
     * @param string $name
     * @return null|ArgumentType
     */
    public function getArgument(string $name): ?ArgumentType
    {
        return $this->compiled()->arguments[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfArguments(): int
    {
        return \count($this->compiled()->arguments);
    }

    /**
     * @return int
     */
    public function getNumberOfRequiredArguments(): int
    {
        return (int)\array_reduce($this->compiled()->arguments, function (?int $carry, ArgumentType $argument): int {
            return (int)$carry + (int)! $argument->hasDefaultValue();
        });
    }

    /**
     * @return int
     */
    public function getNumberOfOptionalArguments(): int
    {
        return (int)\array_reduce($this->compiled()->arguments, function (?int $carry, ArgumentType $argument): int {
            return (int)$carry + (int)$argument->hasDefaultValue();
        });
    }
}
