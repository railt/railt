<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class Argument extends Expression implements NameAwareInterface
{


    public function getName(): string
    {
        return $this->argument->getName();
    }

    public function __toString(): string
    {
        return \sprintf('argument<%s>', $this->getName());
    }
}
