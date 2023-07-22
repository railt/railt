<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Factory;

use Psr\Http\Message\ResponseFactoryInterface as PsrResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Railt\Contracts\Http\Factory\Exception\SerializingExceptionInterface;
use Railt\Contracts\Http\ResponseInterface;

interface ResponseSerializerInterface
{
    /**
     * Encode <, >, ', &, and " characters in the JSON, making it
     * also safe to be embedded into HTML.
     */
    public const DEFAULT_JSON_FLAGS = \JSON_HEX_TAG
                                    | \JSON_HEX_APOS
                                    | \JSON_HEX_AMP
                                    | \JSON_HEX_QUOT
                                    | \JSON_PARTIAL_OUTPUT_ON_ERROR;

    /**
     * @return array{
     *     errors?: list<array{
     *         message: string,
     *         locations?: list<array{
     *             line: int<1, max>,
     *             column: int<1, max>
     *         }>,
     *         path?: list<non-empty-string|int<0, max>>,
     *         extensions?: array<non-empty-string, mixed>
     *     }>,
     *     data?: array|null
     * }
     *
     * @throws SerializingExceptionInterface
     */
    public function toArray(ResponseInterface $response): array;

    /**
     * @param int-mask-of<\JSON_*> $json
     * @return non-empty-string
     *
     * @throws SerializingExceptionInterface
     */
    public function toJson(ResponseInterface $response, int $json = self::DEFAULT_JSON_FLAGS): string;

    /**
     * @param int-mask-of<\JSON_*> $json
     *
     * @throws SerializingExceptionInterface
     */
    public function toResponse(
        PsrResponseFactoryInterface $factory,
        ResponseInterface $response,
        int $json = self::DEFAULT_JSON_FLAGS,
    ): PsrResponseInterface;
}
