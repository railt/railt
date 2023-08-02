<?php

declare(strict_types=1);

namespace Railt\Extension\Router\ParamResolver;

use Psr\EventDispatcher\EventDispatcherInterface;
use Railt\Contracts\Http\InputInterface;
use Railt\Extension\Router\Event\ParameterResolved;
use Railt\Extension\Router\Event\ParameterResolving;
use Railt\TypeSystem\Definition\FieldDefinition;

final class DispatcherAwareParamResolver implements ParamResolverInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    public function resolve(InputInterface $input, \ReflectionParameter $parameter): iterable
    {
        yield from $this->dispatch($input, $parameter);
    }

    /**
     * @param InputInterface<FieldDefinition> $input
     */
    private function dispatch(InputInterface $input, \ReflectionParameter $parameter): array
    {
        $resolving = new ParameterResolving(
            input: $input,
            parameter: $parameter,
        );

        if ($parameter->isDefaultValueAvailable()) {
            $resolving->setValue($parameter->getDefaultValue());
        }

        /** @var ParameterResolving $resolving */
        $resolving = $this->dispatcher->dispatch($resolving);

        if (!$resolving->hasValue()) {
            $message = 'Could not resolve value of #%d ($%s) parameter';
            $message = \sprintf($message, $parameter->getPosition(), $parameter->getName());
            throw new \InvalidArgumentException($message);
        }

        /** @var ParameterResolved $resolved */
        $resolved = $this->dispatcher->dispatch(new ParameterResolved(
            input: $input,
            parameter: $parameter,
            value: $resolving->getValue(),
        ));

        return $resolved->value;
    }
}
