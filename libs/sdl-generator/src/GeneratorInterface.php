<?php

declare(strict_types=1);

namespace Railt\SDL\Generator;

interface GeneratorInterface extends \Stringable
{
    public function __toString(): string;
}
