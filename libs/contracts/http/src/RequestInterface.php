<?php

declare(strict_types=1);

namespace Railt\Contracts\Http;

use Railt\Contracts\Http\Request\OperationNameProviderInterface;
use Railt\Contracts\Http\Request\QueryProviderInterface;
use Railt\Contracts\Http\Request\VariablesProviderInterface;

interface RequestInterface extends
    QueryProviderInterface,
    VariablesProviderInterface,
    OperationNameProviderInterface {}
