<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Support;

/**
 * Class PartiallyAppliedFunction
 */
class PartiallyAppliedFunction implements \Countable
{
    /**
     * The placeholder for the given argument.
     */
    public const PLACEHOLDER = '_';

    /**
     * @var \Closure
     */
    private $context;

    /**
     * @var array
     */
    private $leftArguments = [];

    /**
     * @var array
     */
    private $rightArguments = [];

    /**
     * @var int
     */
    private $maxArguments = 0;

    /**
     * PartiallyAppliedFunction constructor.
     *
     * @param \Closure $callable
     */
    private function __construct(\Closure $callable)
    {
        $this->context = $callable;
    }

    /**
     * @param callable|\ReflectionFunctionAbstract $callable
     * @return $this|static|callable|PartiallyAppliedFunction
     */
    public static function new($callable): callable
    {
        switch (true) {
            case $callable instanceof \Closure:
                return static::fromClosure($callable);

            case \is_callable($callable):
                return static::fromCallable($callable);

            case $callable instanceof \ReflectionMethod:
                return static::fromReflectionMethod($callable);

            case $callable instanceof \ReflectionFunction:
                return static::fromReflectionFunction($callable);
        }

        return static::fromClosure(function () use ($callable) {
            return $callable;
        });
    }

    /**
     * @param \Closure $callable
     * @return $this|static|callable|PartiallyAppliedFunction
     */
    public static function fromClosure(\Closure $callable): self
    {
        $ctx = new static($callable);
        $ctx->maxArguments = self::arguments($callable);

        return $ctx;
    }

    /**
     * @param \Closure $callable
     * @return int
     */
    private static function arguments(\Closure $callable): int
    {
        try {
            $parameters = (new \ReflectionFunction($callable))->getParameters();

            return \count($parameters);
        } catch (\ReflectionException $e) {
            return 1;
        }
    }

    /**
     * @param callable $callable
     * @return $this|static|callable|PartiallyAppliedFunction
     */
    public static function fromCallable(callable $callable): self
    {
        return static::fromClosure(\Closure::fromCallable($callable));
    }

    /**
     * @param \ReflectionMethod $func
     * @return $this|static|callable|PartiallyAppliedFunction
     */
    public static function fromReflectionMethod(\ReflectionMethod $func): self
    {
        $func->setAccessible(true);

        return static::fromClosure($func->getClosure());
    }

    /**
     * @param \ReflectionFunction $func
     * @return $this|static|callable|PartiallyAppliedFunction
     */
    public static function fromReflectionFunction(\ReflectionFunction $func): self
    {
        return static::fromClosure($func->getClosure());
    }

    /**
     * @param mixed ...$args
     * @return $this|static|callable|mixed|PartiallyAppliedFunction
     */
    public function __invoke(...$args)
    {
        $curried = $this->lcurry(...$args);

        if (\count($args) === 0 || $curried->isCompleted()) {
            return $curried->reduce();
        }

        return $curried;
    }

    /**
     * @param mixed ...$args
     * @return $this|static|callable|PartiallyAppliedFunction
     */
    public function lcurry(...$args): self
    {
        $ctx = (clone $this);

        $left = [];

        foreach ($this->leftArguments as $argument) {
            $left[] = $this->isPlaceholder($argument) && \count($args)
                ? \array_shift($args)
                : $argument;
        }

        $ctx->leftArguments = \array_merge($left, $args);

        return $ctx;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function isPlaceholder($value): bool
    {
        return $value === self::PLACEHOLDER;
    }

    /**
     * @return bool
     */
    private function isCompleted(): bool
    {
        return $this->maxArguments <= $this->count();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->filter());
    }

    /**
     * @return array
     */
    private function filter(): array
    {
        $result = \array_merge($this->leftArguments, $this->rightArguments);

        return \array_filter($result, function ($argument): bool {
            return ! $this->isPlaceholder($argument);
        });
    }

    /**
     * @return mixed
     */
    public function reduce()
    {
        return ($this->context)(...$this->filter());
    }

    /**
     * @param mixed ...$args
     * @return $this|static|callable|PartiallyAppliedFunction
     */
    public function rcurry(...$args): self
    {
        $ctx = (clone $this);

        $right = [];

        foreach (\array_reverse($this->rightArguments) as $argument) {
            $right[] = $this->isPlaceholder($argument) && \count($args)
                ? \array_pop($args)
                : $argument;
        }

        $ctx->rightArguments = \array_merge(\array_reverse($right), $args);

        return $ctx;
    }

    /**
     * @return \Closure
     */
    public function uncurry(): \Closure
    {
        return function (...$args) {
            return $this->lcurry(...$args)->reduce();
        };
    }

    /**
     * @param mixed ...$args
     * @return $this|static|callable|PartiallyAppliedFunction
     */
    public function curry(...$args): self
    {
        return $this->lcurry(...$args);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return TypeDumper::dump($this->context);
    }
}
