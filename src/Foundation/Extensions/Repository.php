<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extensions;

use Railt\Container\ContainerInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

/**
 * Class Repository
 */
class Repository
{
    /**
     * @var array|Extension[]
     */
    private $extensions = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * Repository constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $extension
     * @return void
     */
    public function add(string $extension): void
    {
        if (\array_key_exists($extension, $this->extensions)) {
            throw new \InvalidArgumentException('Can not redeclare already registered extension ' . $extension);
        }

        $this->extensions[$extension] = $this->container->make($extension);
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->booted === false) {
            foreach ($this->extensions as $extension) {
                if (\method_exists($extension, 'boot')) {
                    $this->container->call([$extension, 'boot']);
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

        foreach ($this->extensions as $extension) {
            $then = function (RequestInterface $request) use ($extension, $then) {
                return $extension->handle($request, $then);
            };
        }

        return $then($request);
    }
}
