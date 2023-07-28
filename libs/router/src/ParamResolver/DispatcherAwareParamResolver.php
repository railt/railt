<?php

declare(strict_types=1);

namespace Railt\Router\ParamResolver;

use Psr\EventDispatcher\EventDispatcherInterface;
use Railt\Contracts\Http\InputInterface;

final class DispatcherAwareParamResolver implements ParamResolverInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    public function resolve(InputInterface $input, \ReflectionParameter ...$parameters): array
    {
        // TODO: Implement resolve() method.
    }
}
