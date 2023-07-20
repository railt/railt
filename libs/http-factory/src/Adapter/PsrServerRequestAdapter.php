<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Adapter;

use Psr\Http\Message\ServerRequestInterface;
use Railt\Contracts\Http\Factory\AdapterInterface;

/**
 * Expects `psr/http-message` package installation.
 */
final class PsrServerRequestAdapter implements AdapterInterface
{
    public function __construct(
        private readonly ServerRequestInterface $request,
        private readonly StreamReader $reader = new StreamReader(),
    ) {}

    /**
     * @psalm-suppress MixedAssignment : Okay
     */
    public function getQueryParams(): array
    {
        $filtered = [];

        foreach ($this->request->getQueryParams() as $key => $value) {
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

        foreach ((array)$this->request->getParsedBody() as $key => $value) {
            if (\is_string($key) && $key !== '') {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    public function getContentType(): ?string
    {
        return $this->request->getHeaderLine('content-type') ?: null;
    }

    public function getBody(): string
    {
        $stream = $this->request->getBody();

        if (($resource = $stream->detach()) === null) {
            return '';
        }

        return $this->reader->read($resource);
    }
}
