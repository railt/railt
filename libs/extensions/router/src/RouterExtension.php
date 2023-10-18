<?php

declare(strict_types=1);

namespace Railt\Extension\Router;

use Psr\Container\ContainerInterface;
use Railt\EventDispatcher\EventDispatcherInterface;
use Railt\Extension\Router\Instantiator\InstantiatorInterface;
use Railt\Extension\Router\ParamResolver\ParamResolverInterface;
use Railt\Foundation\Extension\ExtensionInterface;
use Railt\TypeSystem\DictionaryInterface;

/**
 * @template-implements ExtensionInterface<RouterContext>
 */
final class RouterExtension implements ExtensionInterface
{
    public function __construct(
        private readonly ?ContainerInterface $container = null,
        private ?InstantiatorInterface $instantiator = null,
        private ?ParamResolverInterface $paramResolver = null,
    ) {
    }

    public function load(DictionaryInterface $schema, EventDispatcherInterface $dispatcher): object
    {
        return new RouterContext(
            dispatcher: $dispatcher,
            container: $this->container,
            instantiator: $this->instantiator,
            paramResolver: $this->paramResolver,
        );
    }

    public function unload(object $context): void
    {
        assert($context instanceof RouterContext);

        $context->dispose();
    }
}
