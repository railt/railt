<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\ArgumentInterface;
use GraphQL\Contracts\TypeSystem\Common\ArgumentsAwareInterface;
use Railt\Common\Iter;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Serafim\Immutable\Immutable;

/**
 * @mixin ArgumentsAwareInterface
 */
trait ArgumentsTrait
{
    /**
     * @psalm-var array<string, ArgumentInterface>
     * @var array|ArgumentInterface[]
     */
    protected array $arguments = [];

    /**
     * {@inheritDoc}
     */
    public function hasArgument(string $name): bool
    {
        return $this->getArgument($name) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getArgument(string $name): ?ArgumentInterface
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param iterable|ArgumentInterface[] $arguments
     * @return void
     */
    public function addArguments(iterable $arguments): void
    {
        foreach ($arguments as $argument) {
            $this->addArgument($argument);
        }
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param iterable|ArgumentInterface[] $arguments
     * @return object|self|$this
     */
    public function withArguments(iterable $arguments): self
    {
        return Immutable::execute(fn() =>  $this->addArguments($arguments));
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param ArgumentInterface $argument
     * @return object|self|$this
     */
    public function withArgument(ArgumentInterface $argument): self
    {
        return Immutable::execute(fn() => $this->addArgument($argument));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param ArgumentInterface $argument
     * @return void
     */
    public function addArgument(ArgumentInterface $argument): void
    {
        if (isset($this->arguments[$argument->getName()])) {
            $message = \sprintf('Argument %s has already been defined', $argument->getName());

            throw new TypeUniquenessException($message);
        }

        $this->arguments[$argument->getName()] = $argument;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param string $name
     * @return object|self|$this
     */
    public function withoutArgument(string $name): self
    {
        return Immutable::execute(fn() => $this->removeArgument($name));
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param string $name
     * @return void
     */
    public function removeArgument(string $name): void
    {
        unset($this->arguments[$name]);
    }
}
