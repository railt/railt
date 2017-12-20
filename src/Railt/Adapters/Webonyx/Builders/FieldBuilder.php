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
use Railt\Adapters\Webonyx\FieldResolver;
use Railt\Adapters\Webonyx\Registry;
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
     */
    public function build(): FieldDefinition
    {
        $config = [
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'type'        => $this->buildType(),
            'args'        => ArgumentBuilder::buildArguments($this->reflection, $this->getRegistry()),
            'resolve'     => $this->getResolver(),
            // complexity
        ];

        if ($this->reflection->isDeprecated()) {
            $config['deprecationReason'] = $this->reflection->getDeprecationReason();
        }

        return FieldDefinition::create($config);
    }

    /**
     * @return \Closure|null
     */
    private function getResolver(): ?\Closure
    {
        /** @var RequestInterface $request */
        $request = $this->resolve(RequestInterface::class);

        /** @var FieldResolver $resolver */
        $resolver = $this->resolve(FieldResolver::class);

        return $resolver->getCallback($request, $this->reflection);
    }
}
