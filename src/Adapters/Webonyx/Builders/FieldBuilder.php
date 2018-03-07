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
use Railt\Adapters\Webonyx\Registry;
use Railt\Adapters\Webonyx\WebonyxInput;
use Railt\Foundation\Events\FieldResolving;
use Railt\Foundation\Events\TypeBuilding;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Dependent\Field\HasFields;
use Railt\Reflection\Contracts\Dependent\FieldDefinition as ReflectionField;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;

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
            if (TypeBuilding::canBuild($dispatcher, $field)) {
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
        $events = $this->make(Dispatcher::class);

        return function ($parent, array $arguments, $context, ResolveInfo $info) use ($events) {
            $input = new WebonyxInput($this->reflection, $info, $arguments);

            $resolving = new FieldResolving($input, $parent);

            /** @var FieldResolving $event */
            $events->dispatch(FieldResolving::class, $resolving);

            if ($resolving->isPropagationStopped()) {
                return $this->reflection->getTypeDefinition() instanceof ObjectDefinition ? [] : null;
            }

            return $resolving->getResponse();
        };
    }
}
