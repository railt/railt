<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Adapters\Event;
use Railt\Events\Dispatcher;
use Railt\Events\Events;
use Railt\Foundation\Extensions\BaseExtension;
use Railt\Io\File;
use Railt\Routing\Contracts\RegistryInterface;
use Railt\Routing\Contracts\RouterInterface;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class RouterServiceProvider
 */
class RouterExtension extends BaseExtension
{
    /**
     * @var string
     */
    private const SCHEMA_ROUTE_FILE = __DIR__ . '/resources/route.graphqls';

    /**
     * @param CompilerInterface $compiler
     * @return void
     */
    public function boot(CompilerInterface $compiler): void
    {
        $this->instance(RouterInterface::class, new Router($this->getContainer()));
        $this->instance(RegistryInterface::class, new Registry());

        $compiler->compile(File::fromPathname(self::SCHEMA_ROUTE_FILE));

        $this->call(\Closure::fromCallable([$this, 'bootFieldResolver']));
    }

    /**
     * @param RouterInterface $router
     * @param Dispatcher|Events $events
     * @throws \InvalidArgumentException
     */
    private function bootFieldResolver(RouterInterface $router, Dispatcher $events): void
    {
        $resolver = new FieldResolver($this->getContainer(), $router, $events);

        $events->delegate(Event::DISPATCHING . ':*', [$resolver, 'handle']);
    }
}
