<?php

declare(strict_types=1);

namespace Railt\Extension\Router\ParamResolver;

use Railt\Contracts\Http\InputInterface;
use Railt\TypeSystem\Definition\FieldDefinition;

interface ParamResolverInterface
{
    /**
     * @param InputInterface<FieldDefinition> $input
     * @return iterable<non-empty-string|int<0, max>, mixed>
     */
    public function resolve(InputInterface $input, \ReflectionParameter $parameter): iterable;
}
