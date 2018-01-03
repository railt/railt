<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\ServiceProviders;

use Railt\Container\Autowireable;
use Railt\Container\Registrable;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

/**
 * Interface ServiceProvider
 */
interface ServiceProvider extends Registrable, Autowireable
{
    /**
     * @param RequestInterface $request
     * @param \Closure $then
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, \Closure $then): ResponseInterface;
}
