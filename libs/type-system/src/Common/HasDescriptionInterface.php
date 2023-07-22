<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

interface HasDescriptionInterface
{
    /**
     * Returns statement description.
     */
    public function getDescription(): ?string;
}
