<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

interface DescriptionAwareInterface
{
    public function getDescription(): ?string;
}
