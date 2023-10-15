<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Input;

/**
 * @psalm-type SelectionType = iterable<non-empty-string, true|iterable>
 * @phpstan-type SelectionType iterable<non-empty-string, true|iterable>
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
     * Returns {@see true} in case of graphql query contains expected field.
     *
     * @param non-empty-string $field
     */
    public function isSelected(string $field): bool;

    /**
     * Returns {@see true} in case of graphql query contains
     * ANY of expected fields or {@see false} otherwise.
     *
     * @param non-empty-string $field
     * @param non-empty-string ...$fields
     */
    public function isSelectedOneOf(string $field, string ...$fields): bool;

    /**
     * Returns {@see true} in case of graphql query contains
     * ALL of expected fields or {@see false} otherwise.
     *
     * @param non-empty-string $field
     * @param non-empty-string ...$fields
     */
    public function isSelectedAllOf(string $field, string ...$fields): bool;

    /**
     * @return iterable<non-empty-string>
     */
    public function getSelectedTypes(): iterable;
}
