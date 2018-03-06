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
     * @return array
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function buildFields(HasFields $type, Registry $registry): array
    {
        $result = [];

        foreach ($type->getFields() as $field) {
            $result[$field->getName()] = (new static($field, $registry))->build();
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
            'args'        => ArgumentBuilder::buildArguments($this->reflection, $this->getRegistry()),
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
            $input = new WebonyxInput($this->reflection, $info, $arguments, $parent);

            $result = $this->dispatching($parent, $this->reflection, $input);

            return $this->dispatched($result, $this->reflection, $input);
        };
    }

    /**
     * @return string
     */
    private function getEventName(): string
    {
        $parent = $this->reflection->getParent();

        return ':' . $parent->getName() . ':' . $this->reflection->getName();
    }

    /**
     * @param mixed $parent
     * @param ReflectionField $field
     * @param InputInterface $input
     * @return mixed
     */
    private function dispatching($parent, ReflectionField $field, InputInterface $input)
    {
        $event = $this->getEventName();

        $args = [$parent, $field, $input];

        return $this->make(Dispatcher::class)->dispatch(Event::DISPATCHING . $event, $args);
    }

    /**
     * @param $result
     * @param ReflectionField $field
     * @param InputInterface $input
     * @return mixed
     */
    private function dispatched($result, ReflectionField $field, InputInterface $input)
    {
        $event = $this->getEventName();

        $args = [$result, $field, $input];

        return $this->make(Dispatcher::class)->dispatch(Event::DISPATCHED . $event, $args) ?? $result;
    }
}
