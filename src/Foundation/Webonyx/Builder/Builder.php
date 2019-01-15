<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Railt\Foundation\Event\Building\TypeBuilding;
use Railt\Foundation\Webonyx\TypeLoader;
use Railt\SDL\Contracts\Behavior\AllowsTypeIndication;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Builder
 */
abstract class Builder implements BuilderInterface
{
    /**
     * @var TypeDefinition
     */
    protected $reflection;

    /**
     * @var EventDispatcherInterface
     */
    protected $events;

    /**
     * @var TypeLoader
     */
    protected $loader;

    /**
     * @var array
     */
    protected $store = [];

    /**
     * Builder constructor.
     * @param TypeDefinition $type
     * @param EventDispatcherInterface $events
     * @param TypeLoader $loader
     */
    public function __construct(TypeDefinition $type, EventDispatcherInterface $events, TypeLoader $loader)
    {
        $this->reflection = $type;
        $this->events     = $events;
        $this->loader     = $loader;
    }

    /**
     * @param TypeDefinition $type
     * @return Type|mixed
     * @throws \Railt\Foundation\Webonyx\Exception\BuilderMissingException
     */
    protected function buildType(TypeDefinition $type)
    {
        return $this->loader->build($type);
    }

    /**
     * @param Event $event
     * @return Event
     */
    protected function fire(Event $event): Event
    {
        return $this->events->dispatch(\get_class($event), $event);
    }

    /**
     * @param AllowsTypeIndication $hint
     * @return Type
     * @throws \Exception
     */
    protected function buildTypeHint(AllowsTypeIndication $hint): Type
    {
        /** @var Type|ObjectType $type */
        $type = $this->loadType($hint->getTypeDefinition()->getName());

        if ($hint->isListOfNonNulls()) {
            $type = Type::listOf(Type::nonNull($type));
        } elseif ($hint->isList()) {
            $type = Type::listOf($type);
        }

        if ($hint->isNonNull()) {
            $type = Type::nonNull($type);
        }

        return $type;
    }

    /**
     * @param string $type
     * @return Type
     */
    protected function loadType(string $type): Type
    {
        return $this->loader->get($type, $this->getReflection());
    }

    /**
     * @return TypeDefinition
     */
    public function getReflection(): TypeDefinition
    {
        return $this->reflection;
    }

    /**
     * @param string $type
     * @return TypeDefinition
     */
    protected function loadReflection(string $type): TypeDefinition
    {
        return $this->loader->getReflection($type, $this->getReflection());
    }

    /**
     * @param TypeDefinition $type
     * @return bool
     */
    protected function shouldSkip(TypeDefinition $type): bool
    {
        $event = $this->events->dispatch(TypeBuilding::class, new TypeBuilding($type));

        return $event->isPropagationStopped();
    }
}
