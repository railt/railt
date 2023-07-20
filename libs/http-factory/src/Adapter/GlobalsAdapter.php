<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Adapter;

use Railt\Contracts\Http\Factory\AdapterInterface;

final class GlobalsAdapter implements AdapterInterface
{
    /**
     * Name of a read-only stream that allows you to read raw data from the
     * request body.
     *
     * @var non-empty-string
     */
    public const PHP_INPUT_STREAM = 'php://input';

    /**
     * @param array<non-empty-string, mixed>|null $get
     * @param array<non-empty-string, mixed>|null $post
     * @param array<non-empty-string, mixed>|null $server
     * @param string|\Stringable|null $body
     */
    public function __construct(
        private readonly ?array $get = null,
        private readonly ?array $post = null,
        private readonly ?array $server = null,
        private readonly string|\Stringable|null $body = null,
        private readonly StreamReader $reader = new StreamReader(),
    ) {}

    public function getQueryParams(): array
    {
        return $this->get ?? $_GET ?? [];
    }

    public function getBodyParams(): array
    {
        return $this->post ?? $_POST ?? [];
    }

    public function getContentType(): ?string
    {
        $server = $this->server ?? $_SERVER ?? [];

        if (isset($server['HTTP_CONTENT_TYPE'])
            && \is_string($server['HTTP_CONTENT_TYPE'])
            && $server['HTTP_CONTENT_TYPE'] !== ''
        ) {
            return $server['HTTP_CONTENT_TYPE'];
        }

        if (isset($server['CONTENT_TYPE'])
            && \is_string($server['CONTENT_TYPE'])
            && $server['CONTENT_TYPE'] !== ''
        ) {
            return $server['CONTENT_TYPE'];
        }

        return null;
    }

    public function getBody(): string
    {
        if ($this->body !== null) {
            return (string)$this->body;
        }

        $stream = \fopen(self::PHP_INPUT_STREAM, 'rb');

        try {
            return $this->reader->read($stream);
        } finally {
            \fclose($stream);
        }
    }
}
