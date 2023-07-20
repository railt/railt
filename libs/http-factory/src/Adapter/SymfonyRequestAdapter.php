<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Adapter;

use Railt\Contracts\Http\Factory\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Expects `symfony/http-foundation` package installation.
 */
final class SymfonyRequestAdapter implements AdapterInterface
{
    public function __construct(
        private readonly Request $request,
        private readonly StreamReader $reader = new StreamReader(),
    ) {}

    /**
     * @psalm-suppress MixedAssignment : Okay
     */
    public function getQueryParams(): array
    {
        $filtered = [];

        foreach ($this->request->query->getIterator() as $key => $value) {
            if (\is_string($key) && $key !== '') {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * @psalm-suppress MixedAssignment : Okay
     */
    public function getBodyParams(): array
    {
        $filtered = [];

        foreach ($this->request->request->getIterator() as $key => $value) {
            if (\is_string($key) && $key !== '') {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    public function getContentType(): ?string
    {
        foreach ($this->request->headers->all('content-type') as $value) {
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    public function getBody(): string
    {
        $stream = $this->request->getContent(true);

        return $this->reader->read($stream);
    }
}
