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
use Railt\Http\RequestInterface;
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
     * @return \Closure|null
     * @throws \InvalidArgumentException
     */
    private function getFieldResolver(): ?\Closure
    {
        $event = $this->getEventName();

        $args  = [$this->reflection, $this->getInputResolver()];

        return $this->make(Dispatcher::class)->dispatch($event, $args);
    }

    /**
     * @return \Closure
     * @throws \InvalidArgumentException
     */
    private function getInputResolver(): \Closure
    {
        return function ($parent, array $args, RequestInterface $http, ResolveInfo $info): InputInterface {
            return new WebonyxInput($this->reflection, $info, $args, $parent);
        };
    }

    /**
     * @return string
     */
    private function getEventName(): string
    {
        $parent = $this->reflection->getParent();

        return Event::DISPATCHING . ':' . $parent->getName() . ':' . $this->reflection->getName();
    }
}
