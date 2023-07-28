<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Psr\Http\Message\ServerRequestInterface;
use Railt\Http\Factory\Exception\ParsingException;

abstract class JsonRequestParser extends GenericRequestParser
{
    protected function providesJsonContent(ServerRequestInterface $request): bool
    {
        $contentType = $request->getHeaderLine('content-type');

        return \str_contains($contentType, '/json')
            || \str_contains($contentType, '+json');
    }

    /**
     * @param int<1, 2147483647> $depth
     */
    protected function jsonDecode(ServerRequestInterface $request, int $depth = 512): ?array
    {
        $json = $this->getContents($request);

        /** @psalm-suppress MixedAssignment */
        try {
            $data = \json_decode($json, true, $depth, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw ParsingException::fromJsonException($e);
        }

        if (\is_array($data)) {
            return $data;
        }

        return null;
    }
}
