<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Error;

/**
 * The GraphQL exception category representation.
 */
interface CategoryInterface
{
    /**
     * Returns string name representation of the exception category.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Returns {@see true} in case of exception category is client-safe
     * and exception message can be shown to the user 'as is' or {@see false}
     * instead.
     */
    public function isClientSafe(): bool;
}
