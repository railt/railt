<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Execution\Common;

use Railt\TypeSystem\Execution\Directive;

interface HasDirectivesInterface
{
    /**
     * @param non-empty-string|null $name
     *
     * @return iterable<Directive>
     */
    public function getDirectives(string $name = null): iterable;

    /**
     * @param non-empty-string $name
     */
    public function hasDirective(string $name): bool;

    /**
     * @param non-empty-string|null $name
     *
     * @return int<0, max>
     */
    public function getNumberOfDirectives(string $name = null): int;
}
