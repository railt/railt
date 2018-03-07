<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Foundation\Events\FieldResolving;
use Railt\Foundation\Extensions\BaseExtension;
use Railt\Foundation\Kernel\Contracts\ClassLoader;
use Railt\Io\File;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\Route\Directive;
use Railt\SDL\Schema\CompilerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;

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

        $callback = function (FieldResolving $event) use ($resolver, $loader, $router) {
            $field = $event->getInput()->getFieldDefinition();

            $this->loadRouteDirectives($field, $loader, $router);

            $event->setResponse($resolver->handle($event->getParentValue(), $event->getInput()));
        };

        $events->addListener(FieldResolving::class, $callback);
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
