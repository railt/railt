<?php

declare(strict_types=1);

namespace Railt\Router\ParamResolver;

use Railt\Contracts\Http\InputInterface;

interface ParamResolverInterface
{
    /**
     * @param \ReflectionParameter ...$parameters
     * @return iterable<non-empty-string|int<0, max>, mixed>
     */
    public function resolve(InputInterface $input, \ReflectionParameter ...$parameters): iterable;
}
