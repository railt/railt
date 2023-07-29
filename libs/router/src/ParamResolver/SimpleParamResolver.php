<?php

declare(strict_types=1);

namespace Railt\Router\ParamResolver;

use Railt\Contracts\Http\Input\ArgumentsProviderInterface;
use Railt\Contracts\Http\Input\PathProviderInterface;
use Railt\Contracts\Http\Input\SelectionProviderInterface;
use Railt\Contracts\Http\InputInterface;
use Railt\Contracts\Http\Request\OperationNameProviderInterface;
use Railt\Contracts\Http\Request\QueryProviderInterface;
use Railt\Contracts\Http\Request\VariablesProviderInterface;
use Railt\Contracts\Http\RequestInterface;

final class SimpleParamResolver implements ParamResolverInterface
{
    public function resolve(InputInterface $input, \ReflectionParameter $parameter): iterable
    {
        $type = $parameter->getType();

        if ($type instanceof \ReflectionNamedType) {
            $definition = $input->getFieldDefinition();
            $request = $input->getRequest();

            switch ($type->getName()) {
                case $definition::class:
                    return yield $definition;

                case $input::class:
                case InputInterface::class:
                case PathProviderInterface::class:
                case ArgumentsProviderInterface::class:
                case SelectionProviderInterface::class:
                    return yield $input;

                case $request::class:
                case RequestInterface::class:
                case QueryProviderInterface::class:
                case VariablesProviderInterface::class:
                case OperationNameProviderInterface::class:
                    return yield $input->getRequest();
            }
        }

        if ($input->hasArgument($parameter->getName())) {
            return yield $input->getArgument($parameter->getName());
        }
    }
}
