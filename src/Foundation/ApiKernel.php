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

/**
 * Class Kernel
 * @package Railt\Foundation
 */
abstract class ApiKernel implements KernelInterface
{
    /**
     * @var Endpoint
     */
    private $endpoint;

    /**
     * ApiKernel constructor.
     * @param Endpoint $endpoint
     */
    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @param string $event
     * @param \Closure $then
     * @return ApiKernel
     */
    protected function on(string $event, \Closure $then): ApiKernel
    {
        $this->endpoint->getEvents()->listen($event, $then);

        return $this;
    }

    /**
     * @return void
     */
    final public function boot(): void
    {
        $this->resolve($this->endpoint->getRouter());
        $this->decorate($this->endpoint->getRouter());
    }
}
