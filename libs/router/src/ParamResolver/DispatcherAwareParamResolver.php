<?php

declare(strict_types=1);

namespace Railt\Router\ParamResolver;

use Psr\EventDispatcher\EventDispatcherInterface;
use Railt\Contracts\Http\InputInterface;
use Railt\Router\Event\ParameterResolved;
use Railt\Router\Event\ParameterResolving;

final class DispatcherAwareParamResolver implements ParamResolverInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    public function resolve(InputInterface $input, \ReflectionParameter ...$parameters): iterable
    {
        foreach ($parameters as $parameter) {
            yield $this->dispatch($input, $parameter);
        }
    }

    private function dispatch(InputInterface $input, \ReflectionParameter $parameter): mixed
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

        if (!$resolving->hasResult()) {
            $message = 'Could not resolve parameter #%d $%s value';
            $message = \sprintf($message, $parameter->getPosition(), $parameter->getName());
            throw new \InvalidArgumentException($message);
        }

        /** @var ParameterResolved $resolved */
        $resolved = $this->dispatcher->dispatch(new ParameterResolved(
            input: $input,
            parameter: $parameter,
            result: $resolving->getValue(),
        ));

        return $resolved->result;
    }
}
