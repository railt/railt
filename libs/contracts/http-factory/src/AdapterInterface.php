<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Factory;

/**
 * Adapter, transforming an arbitrary request into an internal
 * required data set.
 */
interface AdapterInterface
{
    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * @return array<non-empty-string, mixed>
     */
    public function getQueryParams(): array;

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, this method MUST
     * return the contents of $_POST.
     *
     * Otherwise, this method may return any results of deserializing
     * the request body content; as parsing returns structured content, the
     * potential types MUST be arrays or objects only.
     *
     * @return array<non-empty-string, mixed>
     */
    public function getBodyParams(): array;

    /**
     * Retrieve all `Content-Type` header values list.
     *
     * @return non-empty-string|null
     */
    public function getContentType(): ?string;

    /**
     * @return string
     */
    public function getBody(): string;
}
