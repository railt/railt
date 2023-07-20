<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Railt\Contracts\Http\Factory\RequestFactoryInterface;
use Railt\Contracts\Http\Factory\RequestParserInterface;

abstract class GenericRequestParser implements RequestParserInterface
{
    public function __construct(
        protected readonly RequestFactoryInterface $requests,
    ) {}
}
