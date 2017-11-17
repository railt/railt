<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Container\ContainerInterface;
use Railt\Routing\Contracts\InputInterface;
use Railt\Routing\Contracts\Middleware;
use Railt\Routing\Contracts\OutputInterface;
use Railt\Routing\Contracts\RouterInterface;

/**
 * Class Pipeline
 */
class Pipeline
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array|Middleware[]
     */
    private $middleware = [];

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Pipeline constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, RouterInterface $router)
    {
        $this->container = $container;
        $this->router    = $router;
    }

    /**
     * @param string $name
     * @param Middleware[] ...$middleware
     * @return $this
     */
    public function middleware(string $name, Middleware ...$middleware)
    {
        $before = ($this->middleware[$name] ?? []);

        $this->middleware[$name] = \array_merge($before, $middleware);

        return $this;
    }

    public function handle(InputInterface $input): OutputInterface
    {
        foreach ($this->router->find($input->getPath()) as $route) {
            // Check route method
            // Exec middleware
            // Process output
        }
    }
}
