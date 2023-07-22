<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Composite;

use Psr\Http\Message\ServerRequestInterface;

final class SelectiveParser extends Composite
{
    public function createFromServerRequest(ServerRequestInterface $request): iterable
    {
        $hasResult = false;

        foreach ($this->parsers as $parser) {
            foreach ($parser->createFromServerRequest($request) as $parsed) {
                $hasResult = true;

                yield $parsed;
            }

            if ($hasResult) {
                break;
            }
        }
    }
}
