<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Adapter;

use Railt\Contracts\Http\Factory\AdapterInterface;

final class DataProviderAdapter implements AdapterInterface
{
    /**
     * @var array<non-empty-string, mixed>
     */
    private readonly array $queryParams;

    /**
     * @var array<non-empty-string, mixed>
     */
    private readonly array $bodyParams;

    /**
     * @param iterable<non-empty-string, mixed> $queryParams
     * @param iterable<non-empty-string, mixed> $bodyParams
     * @param non-empty-string|null $contentType
     * @param string|\Stringable $body
     */
    public function __construct(
        iterable $queryParams = [],
        iterable $bodyParams = [],
        private readonly ?string $contentType = null,
        private readonly string|\Stringable $body = '',
    ) {
        $this->queryParams = [...$queryParams];
        $this->bodyParams = [...$bodyParams];
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getBodyParams(): array
    {
        return $this->bodyParams;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function getBody(): string
    {
        return (string)$this->body;
    }
}
