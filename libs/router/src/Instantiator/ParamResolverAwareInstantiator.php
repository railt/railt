<?php

declare(strict_types=1);

namespace Railt\Router\Instantiator;

use Railt\Contracts\Http\InputInterface;
use Railt\Router\ParamResolver\ParamResolverInterface;

final class ParamResolverAwareInstantiator implements InstantiatorInterface
{
    /**
     * @param InputInterface<object> $input
     */
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

        $arguments = [];

        foreach ($constructor->getParameters() as $parameter) {
            foreach ($this->resolver->resolve($this->input, $parameter) as $value) {
                $arguments[] = $value;
            }
        }

        return $reflection->newInstanceArgs($arguments);
    }
}
