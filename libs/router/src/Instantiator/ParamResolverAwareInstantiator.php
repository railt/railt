<?php

declare(strict_types=1);

namespace Railt\Router\Instantiator;

use Railt\Contracts\Http\InputInterface;
use Railt\Router\ParamResolver\ParamResolverInterface;

final class ParamResolverAwareInstantiator implements InstantiatorInterface
{
    public function __construct(
        private readonly ParamResolverInterface $resolver,
        private readonly InputInterface $input,
    ) {}

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function create(string $class): object
    {
        $reflection = new \ReflectionClass($class);

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstanceArgs();
        }

        $arguments = $this->resolver->resolve($this->input, ...$constructor->getParameters());

        return $reflection->newInstanceArgs($arguments);
    }
}
