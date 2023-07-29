<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Input;

/**
 * @psalm-type SelectionType = iterable<non-empty-string, true|SelectionType>
 * @phpstan-type SelectionType iterable<non-empty-string, true|SelectionType>
 */
interface SelectionProviderInterface
{
    /**
     * @return iterable<non-empty-string>
     */
    public function getSelectedFields(): iterable;

    /**
     * @param int<0, max> $depth
     *
     * @return iterable<non-empty-string, true|SelectionType>
     */
    public function getSelection(int $depth = 0): iterable;

    /**
     * @return iterable<non-empty-string>
     */
    public function getSelectedTypes(): iterable;
}
