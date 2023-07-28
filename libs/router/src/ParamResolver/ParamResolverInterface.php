<?php

declare(strict_types=1);

namespace Railt\Router\ParamResolver;

use Railt\Contracts\Http\InputInterface;

interface ParamResolverInterface
{
    /**
     * @param \ReflectionParameter ...$parameters
     * @return list<mixed>
     */
    public function resolve(InputInterface $input, \ReflectionParameter ...$parameters): array;
}
