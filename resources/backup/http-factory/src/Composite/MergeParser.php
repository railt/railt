<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Composite;

use Psr\Http\Message\ServerRequestInterface;

final class MergeParser extends Composite
{
    public function createFromServerRequest(ServerRequestInterface $request): iterable
    {
        foreach ($this->parsers as $parser) {
            yield from $parser->createFromServerRequest($request);
        }
    }
}
