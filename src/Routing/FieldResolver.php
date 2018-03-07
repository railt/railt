<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\Resolvers\Factory;
use Railt\Routing\Resolvers\Resolver;
use Railt\Routing\Store\Box;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class FieldResolver
 */
class FieldResolver
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * FieldResolver constructor.
     * @param RouterInterface $router
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(RouterInterface $router, EventDispatcherInterface $dispatcher)
    {
        $this->router   = $router;
        $this->resolver = new Factory($dispatcher);
    }

    /**
     * @param InputInterface $input
     * @param Box $parent
     * @return array|mixed
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function handle(InputInterface $input, ?Box $parent)
    {
        $field = $input->getFieldDefinition();

        foreach ($this->router->get($field) as $route) {
            if (! $route->matchOperation($input->getOperation())) {
                continue;
            }

            return $this->resolver->call($input, $route, $parent);
        }

        if ($parent === null && $field->getTypeDefinition() instanceof ObjectDefinition && $field->isNonNull()) {
            return [];
        }

        return $parent[$field->getName()] ?? null;
    }
}
