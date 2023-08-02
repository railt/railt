<?php

declare(strict_types=1);

namespace Railt\Contracts\Http;

use Railt\Contracts\Http\Response\DataProviderInterface;
use Railt\Contracts\Http\Response\ExceptionProviderInterface;

interface ResponseInterface extends
    ExceptionProviderInterface,
    DataProviderInterface
{
    /**
     * @return array{data?:mixed, errors?:list<array>}
     */
    public function toArray(): array;
}
