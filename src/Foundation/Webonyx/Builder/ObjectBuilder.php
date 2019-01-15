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
use Railt\SDL\Contracts\Definitions\ObjectDefinition;

/**
 * Class ObjectBuilder
 * @property ObjectDefinition $reflection
 */
class ObjectBuilder extends Builder
{
    /**
     * @return ObjectType
     * @throws \GraphQL\Error\Error
     */
    public function build(): ObjectType
    {
        return new ObjectType(\array_filter([
            'name'              => $this->reflection->getName(),
            'description'       => $this->reflection->getDescription(),
            'deprecationReason' => $this->reflection->getDeprecationReason(),
            'fields'            => $this->getFields(),
            'interfaces'        => $this->buildInterfaces(),
        ]));
    }

    /**
     * @return \Closure
     */
    private function getFields(): \Closure
    {
        return function (): array {
            $fields = [];

            foreach ($this->reflection->getFields() as $field) {
                $fields[$field->getName()] = $this->buildType($field);
            }

            return $fields;
        };
    }

    /**
     * @return array
     */
    private function buildInterfaces(): array
    {
        $result = [];

        foreach ($this->reflection->getInterfaces() as $interface) {
            $result[] = $this->loadType($interface->getName());
        }

        return $result;
    }
}
