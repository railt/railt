<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Psr\Http\Message\ServerRequestInterface;
use Railt\Contracts\Http\Factory\RequestFactoryInterface;
use Railt\Contracts\Http\Factory\RequestParserInterface;
use Railt\Http\Factory\GraphQLRequestFactory;

abstract class GenericRequestParser implements RequestParserInterface
{
    public function __construct(
        protected readonly RequestFactoryInterface $requests,
    ) {}

    protected function looksLikeGraphQLRequest(mixed $data): bool
    {
        return \is_array($data) && isset($data[GraphQLRequestFactory::FIELD_QUERY]);
    }

    protected function getStreamReader(): StreamReader
    {
        return new StreamReader();
    }

    protected function getContents(ServerRequestInterface $request): string
    {
        $reader = $this->getStreamReader();

        return $reader->read($request->getBody());
    }
}
