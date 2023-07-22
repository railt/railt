<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

interface HasNameInterface
{
    /**
     * Returns identifier of the statement or expression.
     *
     * @return non-empty-string
     */
    public function getName(): string;
}
