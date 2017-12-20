<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\ServiceProviders;

use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Container\ContainerInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Routing\GraphQL\RouterDocument;
use Railt\Routing\Router;

/**
 * Class RouterServiceProvider
 */
class RouterServiceProvider extends BaseServiceProvider
{
    /**
     * @param CompilerInterface $compiler
     * @return void
     */
    public function boot(CompilerInterface $compiler): void
    {
        $this->register(Router::class, function (ContainerInterface $container) {
            return new Router($container);
        });

        $compiler->add(new RouterDocument());
    }

    /**
     * @param RequestInterface $request
     * @param \Closure $then
     * @param array ...$params
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, \Closure $then, ...$params): ResponseInterface
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
