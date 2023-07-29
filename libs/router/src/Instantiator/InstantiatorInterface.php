<?php

declare(strict_types=1);

namespace Railt\Router\Instantiator;

interface InstantiatorInterface
{
    /**
     * @template TObject of object
     *
     * @param class-string<TObject> $class
     *
     * @return TObject
     */
    public function create(string $class): object;
}
