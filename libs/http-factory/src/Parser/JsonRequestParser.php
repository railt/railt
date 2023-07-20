<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Railt\Contracts\Http\Factory\AdapterInterface;
use Railt\Http\Factory\Exception\ParsingException;

abstract class JsonRequestParser extends GenericRequestParser
{
    protected function providesJsonContent(AdapterInterface $adapter): bool
    {
        $contentType = $adapter->getContentType();

        if ($contentType === null) {
            return false;
        }

        return \str_contains($contentType, '/json')
            || \str_contains($contentType, '+json');
    }

    protected function jsonDecode(AdapterInterface $adapter): ?array
    {
        $json = $adapter->getBody();

        /** @psalm-suppress MixedAssignment */
        try {
            $data = \json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw ParsingException::fromJsonException($e);
        }

        if (\is_array($data)) {
            return $data;
        }

        return null;
    }
}
