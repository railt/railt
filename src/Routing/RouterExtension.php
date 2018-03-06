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
use Railt\Foundation\Extensions\BaseExtension;
use Railt\Foundation\Kernel\Contracts\ClassLoader;
use Railt\Io\File;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\Route\Directive;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class RouterServiceProvider
 */
class RouterExtension extends BaseExtension
{
    /**
     * @param CompilerInterface $compiler
     * @return void
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function boot(CompilerInterface $compiler): void
    {
        $this->instance(RouterInterface::class, new Router($this->getContainer()));

        $compiler->compile(File::fromPathname(__DIR__ . '/resources/route.graphqls'));

        $this->call(\Closure::fromCallable([$this, 'bootFieldResolver']));
    }

    /**
     * @param RouterInterface $router
     * @param Dispatcher $events
     * @param ClassLoader $loader
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function bootFieldResolver(RouterInterface $router, Dispatcher $events, ClassLoader $loader): void
    {
        $resolver = new FieldResolver($router, new ActionResolver($events));

        $callback = function (string $event, array $args) use ($resolver, $loader, $router) {
            [$parent, $field, $input] = $args;

            $this->loadRouteDirectives($field, $loader, $router);

            return $resolver->handle($parent, $field, $input);
        };

        $events->listen(Event::ROUTE_DISPATCHING . '*', $callback);

        $events->listen('*', function (string $event): void {
            \Log::info($event);
        });
    }

    /**
     * @param FieldDefinition $field
     * @param ClassLoader $loader
     * @param RouterInterface $router
     */
    private function loadRouteDirectives(FieldDefinition $field, ClassLoader $loader, RouterInterface $router): void
    {
        foreach (['route', 'query', 'mutation', 'subscription'] as $route) {
            foreach ($field->getDirectives($route) as $directive) {
                $router->add(new Directive($this->getContainer(), $directive, $loader));
            }
        }
    }
}
