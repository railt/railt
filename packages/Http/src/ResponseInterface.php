<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Common\RenderableInterface;
use Railt\Http\Exception\ExceptionsProviderInterface;
use Railt\Http\Extension\ExtensionsProviderInterface;
use Railt\Http\Response\DataProviderInterface;

/**
 * Interface ResponseInterface
 */
interface ResponseInterface extends
    RenderableInterface,
    ExtensionsProviderInterface,
    ExceptionsProviderInterface,
    DataProviderInterface
{
}
