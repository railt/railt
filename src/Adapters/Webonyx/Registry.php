<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx;

use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\Type;
use Railt\Adapters\Event;
use Railt\Adapters\Webonyx\Builders\TypeBuilder;
use Railt\Container\ContainerInterface;
use Railt\Events\Dispatcher;
use Railt\Reflection\Contracts\Definitions;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\DependentDefinition;

/**
 * Class Registry
 */
class Registry
{
    private const BUILDER_MAPPINGS = [
        Definitions\ObjectDefinition::class    => Builders\ObjectBuilder::class,
        Definitions\InterfaceDefinition::class => Builders\InterfaceBuilder::class,
        Definitions\DirectiveDefinition::class => Builders\DirectiveBuilder::class,
        Definitions\ScalarDefinition::class    => Builders\ScalarBuilder::class,
        Definitions\EnumDefinition::class      => Builders\EnumBuilder::class,
        Definitions\InputDefinition::class     => Builders\InputBuilder::class,
        Definitions\UnionDefinition::class     => Builders\UnionBuilder::class,
    ];

    /**
     * @var array|Type[]
     */
    private $types = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * Registry constructor.
     * @param ContainerInterface $container
     * @param Dispatcher $events
     */
    public function __construct(ContainerInterface $container, Dispatcher $events)
    {
        $this->container = $container;
        $this->events = $events;
    }

    /**
     * @param TypeDefinition $type
     * @param Dispatcher $events
     * @return bool
     */
    public static function canBuild(TypeDefinition $type, Dispatcher $events): bool
    {
        $event = $type->getName();

        if ($type instanceof DependentDefinition) {
            $event = $type->getParent()->getName() . ':' . $event;
        }

        return $events->dispatch(Event::BUILDING . $event, $type) !== false;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param TypeDefinition $definition
     * @return Type|Directive
     * @throws \InvalidArgumentException
     */
    public function get(TypeDefinition $definition)
    {
        $name = $definition->getName();

        if (! \array_key_exists($name, $this->types)) {
            $this->types[$name] = $this->build($definition);
        }

        return $this->types[$name];
    }

    /**
     * @param TypeDefinition $definition
     * @return Type|Directive
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function build(TypeDefinition $definition)
    {
        if (self::canBuild($definition, $this->events)) {
            return $this->getBuilder($definition)->build();
        }

        $error = 'Can not build a type %s, because This type was not found or it was excluded by the building event';
        throw new \RuntimeException(\sprintf($error, $definition));
    }

    /**
     * @param TypeDefinition $definition
     * @return TypeBuilder
     * @throws \InvalidArgumentException
     */
    private function getBuilder(TypeDefinition $definition): TypeBuilder
    {
        /** @var TypeBuilder $builder */
        $builder = $this->getMapping($definition);

        return new $builder($definition, $this, $this->events);
    }

    /**
     * @param TypeDefinition $definition
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getMapping(TypeDefinition $definition): string
    {
        foreach (self::BUILDER_MAPPINGS as $contract => $builder) {
            if ($definition instanceof $contract) {
                return $builder;
            }
        }

        $error = 'Can not find an allowable Builder for the %s';
        throw new \InvalidArgumentException(\sprintf($error, $definition));
    }
}
