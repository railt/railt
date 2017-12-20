<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\ServiceProviders;

use Railt\Container\ContainerInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

/**
 * Class Pipeline
 */
class Pipeline
{
    /**
     * @var array|ServiceProvider[]
     */
    private $providers = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * Pipeline constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $provider
     * @return void
     */
    public function add(string $provider): void
    {
        $this->providers[] = new $provider($this->container);
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->booted === false) {
            foreach ($this->providers as $provider) {
                if (\method_exists($provider, 'boot')) {
                    $this->container->call([$provider, 'boot']);
                }
            }

            $this->booted = true;
        }
    }

    /**
     * @param RequestInterface $request
     * @param \Closure $response
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, \Closure $response): ResponseInterface
    {
        $then = $response;

        foreach ($this->providers as $provider) {
            $then = function(RequestInterface $request) use ($provider, $then) {
                return $provider->handle($request, $then);
            };
        }

        return $then($request);
    }
}
