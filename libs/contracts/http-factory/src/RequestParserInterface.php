<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Railt\Contracts\Http\Factory\Exception\ParsingExceptionInterface;
use Railt\Contracts\Http\RequestInterface;

interface RequestParserInterface
{
    /**
     * Creates a new GraphQL requests list from arbitrary request data provider.
     *
     * @return iterable<RequestInterface>
     *
     * @throws ParsingExceptionInterface
     */
    public function createFromServerRequest(ServerRequestInterface $request): iterable;
}
