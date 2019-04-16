<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx;

use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Railt\Foundation\Event\Building\BuildingEvent;
use Railt\Foundation\Event\Building\TypeBuilding;
use Railt\Foundation\Webonyx\Builder\BuilderInterface;
use Railt\Foundation\Webonyx\Exception\BuilderMissingException;
use Railt\Component\SDL\Contracts\Definitions;
use Railt\Component\SDL\Contracts\Definitions\ObjectDefinition;
use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;
use Railt\Component\SDL\Contracts\Dependent;
use Railt\Component\SDL\Reflection\Dictionary;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class TypeLoader
 */
class TypeLoader
{
    /**
     * @var string[]
     */
    private const BUILDERS_MAPPING = [
        ObjectDefinition::class                => Builder\ObjectBuilder::class,
        Definitions\InterfaceDefinition::class => Builder\InterfaceBuilder::class,
        Definitions\InputDefinition::class     => Builder\InputBuilder::class,
        Definitions\UnionDefinition::class     => Builder\UnionBuilder::class,
        Definitions\EnumDefinition::class      => Builder\EnumBuilder::class,
        Definitions\ScalarDefinition::class    => Builder\ScalarBuilder::class,
        Definitions\DirectiveDefinition::class => Builder\DirectiveBuilder::class,
        Dependent\FieldDefinition::class       => Builder\FieldBuilder::class,
    ];

    /**
     * @var array|Type[]
     */
    private $types = [];

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var EventDispatcherInterface
     */
    private $events;

    /**
     * TypeLoader constructor.
     *
     * @param EventDispatcherInterface $events
     * @param Dictionary $dictionary
     */
    public function __construct(EventDispatcherInterface $events, Dictionary $dictionary)
    {
        $this->dictionary = $dictionary;
        $this->events = $events;
    }

    /**
     * @param string $type
     * @return Type
     */
    public function __invoke(string $type)
    {
        return $this->get($type);
    }

    /**
     * @param string $type
     * @param TypeDefinition|null $from
     * @return Type|Directive
     */
    public function get(string $type, TypeDefinition $from = null)
    {
        return $this->resolve($type, function (string $type) use ($from) {
            if ($definition = $this->getReflection($type, $from)) {
                return $this->build($definition);
            }

            return null;
        });
    }

    /**
     * @param string $type
     * @param \Closure $otherwise
     * @return Type|Directive
     */
    private function resolve(string $type, \Closure $otherwise)
    {
        if (isset($this->types[$type])) {
            return $this->types[$type];
        }

        return $this->types[$type] = $otherwise($type);
    }

    /**
     * @param string $name
     * @param TypeDefinition|null $from
     * @return TypeDefinition
     */
    public function getReflection(string $name, TypeDefinition $from = null): TypeDefinition
    {
        return $this->dictionary->get($name, $from);
    }

    /**
     * @param TypeDefinition $type
     * @return Type|Schema|null
     * @throws BuilderMissingException
     */
    public function build(TypeDefinition $type)
    {
        $event = $this->fire($type);

        if ($event->isPropagationStopped()) {
            return null;
        }

        return $this->getBuilder($type)->build();
    }

    /**
     * @param TypeDefinition $type
     * @return BuildingEvent|Event
     */
    private function fire(TypeDefinition $type): BuildingEvent
    {
        return $this->events->dispatch(TypeBuilding::class, new TypeBuilding($type));
    }

    /**
     * @param TypeDefinition $type
     * @return BuilderInterface
     * @throws BuilderMissingException
     */
    private function getBuilder(TypeDefinition $type): BuilderInterface
    {
        foreach (self::BUILDERS_MAPPING as $original => $builder) {
            if ($type instanceof $original) {
                return new $builder($type, $this->events, $this);
            }
        }

        $document = $type->getDocument();
        $error = \sprintf('No available builder mappings found for type %s', $type);

        $exception = new BuilderMissingException($error);
        $exception->throwsIn($document->getFile(), $type->getDeclarationLine(), $type->getDeclarationColumn());

        throw $exception;
    }
}
