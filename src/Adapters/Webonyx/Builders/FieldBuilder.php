<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ResolveInfo;
use Railt\Adapters\Event;
use Railt\Adapters\Webonyx\Registry;
use Railt\Adapters\Webonyx\WebonyxInput;
use Railt\Events\Dispatcher;
use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Dependent\Field\HasFields;
use Railt\Reflection\Contracts\Dependent\FieldDefinition as ReflectionField;

/**
 * @property ReflectionField $reflection
 */
class FieldBuilder extends DependentDefinitionBuilder
{
    /**
     * @param HasFields $type
     * @param Registry $registry
     * @param Dispatcher $dispatcher
     * @return array
     * @throws \Exception
     */
    public static function buildFields(HasFields $type, Registry $registry, Dispatcher $dispatcher): array
    {
        $result = [];

        foreach ($type->getFields() as $field) {
            if (Registry::canBuild($field, $dispatcher)) {
                $result[$field->getName()] = (new static($field, $registry, $dispatcher))->build();
            }
        }

        return $result;
    }

    /**
     * @return FieldDefinition
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function build(): FieldDefinition
    {
        $config = [
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'type'        => $this->buildType(),
            'args'        => ArgumentBuilder::buildArguments($this->reflection, $this->getRegistry(), $this->events),
            'resolve'     => $this->getFieldResolver(),
        ];

        if ($this->reflection->isDeprecated()) {
            $config['deprecationReason'] = $this->reflection->getDeprecationReason();
        }

        return FieldDefinition::create($config);
    }

    /**
     * @return \Closure
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function getFieldResolver(): \Closure
    {
        $event = $this->getEventName();

        return function ($parent, array $arguments, $context, ResolveInfo $info) use ($event) {
            $input = new WebonyxInput($this->reflection, $info, $arguments);

            $result = $this->dispatching($parent, $input);

            return $this->dispatched($result, $input);
        };
    }

    /**
     * @return string
     */
    private function getEventName(): string
    {
        $parent = $this->reflection->getParent();

        return $parent->getName() . ':' . $this->reflection->getName();
    }

    /**
     * @param mixed $parent
     * @param InputInterface $input
     * @return mixed
     */
    private function dispatching($parent, InputInterface $input)
    {
        $event = $this->getEventName();

        $args = [$parent, $input];

        return $this->make(Dispatcher::class)->dispatch(Event::ROUTE_DISPATCHING . $event, $args);
    }

    /**
     * @param $result
     * @param InputInterface $input
     * @return mixed
     */
    private function dispatched($result, InputInterface $input)
    {
        $event = $this->getEventName();

        $args = [$result, $input];

        return $this->make(Dispatcher::class)
                ->dispatch(Event::ROUTE_DISPATCHED . $event, $args) ?? $result;
    }
}
