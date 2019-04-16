<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Http;

use Railt\Component\Http\RequestInterface;

/**
 * Interface ProvidesRequest
 */
interface ProvidesRequest
{
    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface;
}
