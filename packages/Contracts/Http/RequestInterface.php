<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Contracts\Http;

use Railt\Contracts\Common\ArrayableInterface;
use Railt\Contracts\Common\JsonableInterface;
use Railt\Contracts\Common\StringableInterface;
use Railt\Contracts\Http\Request\OperationNameInterface;
use Railt\Contracts\Http\Request\QueryInterface;
use Railt\Contracts\Http\Request\VariablesInterface;

/**
 * Interface RequestInterface
 */
interface RequestInterface extends
    QueryInterface,
    VariablesInterface,
    OperationNameInterface,
    ArrayableInterface,
    JsonableInterface,
    StringableInterface
{
}
