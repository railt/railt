<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

interface NameAwareInterface
{
    /**
     * @return non-empty-string
     */
    public function getName(): string;
}