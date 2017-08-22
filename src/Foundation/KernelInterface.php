<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Endpoint;
use Railt\Routing\Router;

/**
 * Interface KernelInterface
 * @package Railt\Foundation
 */
interface KernelInterface
{
    /**
     * KernelInterface constructor.
     * @param Endpoint $endpoint
     */
    public function __construct(Endpoint $endpoint);

    /**
     * @param Router $router
     */
    public function resolve(Router $router): void;

    /**
     * @param Router $router
     */
    public function decorate(Router $router): void;

    /**
     * @return void
     */
    public function boot(): void;
}
