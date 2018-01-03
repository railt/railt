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
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\GraphQL\RouterDocument;
use Railt\Routing\Router;

/**
 * Class RouterServiceProvider
 */
class RouterServiceProvider extends BaseServiceProvider
{
    /**
     * @var CompilerInterface
     */
    private $compiler;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param CompilerInterface $compiler
     * @return void
     */
    public function boot(CompilerInterface $compiler): void
    {
        $this->compiler = $compiler;
        $this->router   = new Router($this->getContainer());

        $this->instance(RouterInterface::class, $this->router);

        $compiler->add(new RouterDocument());
    }
}
