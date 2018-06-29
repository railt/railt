<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Foundation\Events\ActionDispatching;
use Railt\Foundation\Events\FieldResolving;
use Railt\Foundation\Extensions\BaseExtension;
use Railt\Http\InputInterface;
use Railt\Io\File;
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\Route\Directive;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Schema\CompilerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;

/**
 * Class RouterServiceProvider
 */
class RouterExtension extends BaseExtension
{
    private $loadedRoutes = [];

    /**
     * @param CompilerInterface $compiler
     * @param Dispatcher $events
     * @param ClassLoader $loader
     * @return void
     * @throws \InvalidArgumentException
     */
    public function boot(CompilerInterface $compiler, Dispatcher $events, ClassLoader $loader): void
    {
        $this->loadSchema($compiler);

        $router = $this->getRouter();

        $this->bootFieldResolver($events, $router, $loader);
        $this->bootArgumentsInjector($events);
    }

    /**
     * @return RouterInterface
     */
    private function getRouter(): RouterInterface
    {
        $router = new Router($this->getContainer());

        $this->instance(RouterInterface::class, $router);

        return $router;
    }

    /**
     * @param CompilerInterface $compiler
     */
    private function loadSchema(CompilerInterface $compiler): void
    {
        $compiler->compile(File::fromPathname(__DIR__ . '/resources/route.graphqls'));
    }

    /**
     * @param Dispatcher $events
     */
    private function bootArgumentsInjector(Dispatcher $events): void
    {
        $events->addListener(ActionDispatching::class, function (ActionDispatching $event): void {
            $input = $event->getInput();

            $event->addParameter(InputInterface::class, $input);
            $event->addParameter(TypeDefinition::class, $input->getFieldDefinition());
            $event->addParameter(FieldDefinition::class, $input->getFieldDefinition());

            $event->addParameters($input->all());
        });
    }

    /**
     * @param RouterInterface $router
     * @param Dispatcher $events
     * @param ClassLoader $loader
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function bootFieldResolver(Dispatcher $events, RouterInterface $router, ClassLoader $loader): void
    {
        $resolver = new FieldResolver($router, $events, $this->getContainer());

        $callback = function (FieldResolving $event) use ($resolver, $loader, $router): void {
            $field = $event->getInput()->getFieldDefinition();

            $this->loadRouteDirectives($field, $loader, $router);

            $event->setResponse($resolver->handle($event->getInput(), $event->getParentValue()));
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
        if (isset($this->loadedRoutes[$field->getUniqueId()])) {
            return;
        }

        foreach (['route', 'query', 'mutation', 'subscription'] as $directiveName) {
            foreach ($field->getDirectives($directiveName) as $directive) {
                $action = new Directive($this->getContainer(), $directive, $loader);

                foreach ($field->getDirectives('relation') as $join) {
                    $action->relation(
                        (string)$join->getPassedArgument('child'),
                        (string)$join->getPassedArgument('parent')
                    );
                }

                $router->add($action);
            }
        }

        $this->loadedRoutes[$field->getUniqueId()] = true;
    }
}
