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
use Railt\Contracts\Common\RenderableInterface;
use Railt\Contracts\Http\Response\DataInterface;
use Railt\Contracts\Http\Response\ErrorsProviderInterface;
use Railt\Contracts\Http\Response\ExceptionsProviderInterface;
use Railt\Contracts\Http\Response\ExtensionsProviderInterface;

/**
 * Interface ResponseInterface
 */
interface ResponseInterface extends
    ExceptionsProviderInterface,
    ExtensionsProviderInterface,
    ErrorsProviderInterface,
    DataInterface,
    ArrayableInterface,
    JsonableInterface,
    RenderableInterface
{
}
