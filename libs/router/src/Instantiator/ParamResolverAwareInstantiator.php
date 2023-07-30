<?php

declare(strict_types=1);

namespace Railt\Router\Instantiator;

use Railt\Contracts\Http\InputInterface;
use Railt\Router\ParamResolver\ParamResolverInterface;
use Railt\TypeSystem\Definition\FieldDefinition;

final class ParamResolverAwareInstantiator implements InstantiatorInterface
{
    /**
     * @param InputInterface<FieldDefinition> $input
     */
    public function __construct(
        private readonly ParamResolverInterface $resolver,
        private readonly InputInterface $input,
    ) {
    }

    /**
     * @template TObject of object
     *
     * @param class-string<TObject> $class
     *
     * @return TObject
     *
     * @throws \ReflectionException
     *
     * @psalm-suppress MixedAssignment
     */
    public function create(string $class): object
    {
        $reflection = new \ReflectionClass($class);

        $constructor = $reflection->getConstructor();

        if ($constructor === null || $constructor->getNumberOfParameters() === 0) {
            /** @psalm-suppress MixedMethodCall */
            return new $class();
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
