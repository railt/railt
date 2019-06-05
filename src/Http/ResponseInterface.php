<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Extension\ExtensionProviderInterface;
use Railt\Http\Response\DataProviderInterface;
use Railt\Http\Response\ExceptionsProviderInterface;

/**
 * Interface ResponseInterface
 */
interface ResponseInterface extends
    ExtensionProviderInterface,
    ExceptionsProviderInterface,
    DataProviderInterface,
    RenderableInterface
{
}
