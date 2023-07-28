<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Composite;

use Railt\Contracts\Http\Factory\RequestParserInterface;

abstract class Composite implements RequestParserInterface
{
    /**
     * @var list<RequestParserInterface>
     */
    protected readonly array $parsers;

    /**
     * @param iterable<RequestParserInterface> $parsers
     */
    public function __construct(
        iterable $parsers = [],
    ) {
        /** @psalm-suppress InvalidOperand : false-positive */
        $this->parsers = [...$parsers];
    }
}
