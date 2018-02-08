<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Foundation\ServiceProviders\BaseServiceProvider;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Routing\Contracts\RegistryInterface;
use Railt\Routing\Contracts\RouterInterface;
use Railt\SDL\Schema\CompilerInterface;

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
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @param CompilerInterface $compiler
     * @return void
     */
    public function boot(CompilerInterface $compiler): void
    {
        $this->compiler = $compiler;
        $this->router   = new Router($this->getContainer());
        $this->registry = new Registry();

        $this->instance(RouterInterface::class, $this->router);
        $this->instance(RegistryInterface::class, $this->registry);

        $compiler->compile($this->getRouteDirective());
    }

    /**
     * @return Readable
     */
    private function getRouteDirective(): Readable
    {
        return File::fromPathname(__DIR__.'/graphql/route.graphqls');
    }
}
