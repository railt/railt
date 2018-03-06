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
use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Contracts\RegistryInterface;
use Railt\Routing\Contracts\RouterInterface;

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
     * @var Dispatcher
     */
    private $events;

    /**
     * @var ActionResolver
     */
    private $resolver;

    /**
     * FieldResolver constructor.
     * @param RouterInterface $router
     * @param Dispatcher $events
     * @param ActionResolver $resolver
     */
    public function __construct(RouterInterface $router, Dispatcher $events, ActionResolver $resolver)
    {
        $this->router = $router;
        $this->events = $events;
        $this->resolver = $resolver;
    }

    /**
     * @param $parent
     * @param FieldDefinition $field
     * @param InputInterface $input
     * @return array|mixed
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function handle($parent, FieldDefinition $field, InputInterface $input)
    {
        foreach ($this->router->get($field) as $route) {
            if (! $route->matchOperation($input->getOperation())) {
                continue;
            }

            $parameters = \array_merge($input->all(), [
                InputInterface::class => $input,
                TypeDefinition::class => $field,
            ]);

            return $this->resolver->call($field, $route, $input, $parameters);
        }

        if ($parent === null && $field->getTypeDefinition() instanceof ObjectDefinition && $field->isNonNull()) {
            return [];
        }

        return $parent[$field->getName()] ?? null;
    }
}
