<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\InterfaceType;
use Railt\Foundation\Webonyx\Builder\Common\TypeResolverTrait;
use Railt\SDL\Contracts\Definitions\InterfaceDefinition;

/**
 * Class InterfaceBuilder
 * @property InterfaceDefinition $reflection
 */
class InterfaceBuilder extends Builder
{
    use TypeResolverTrait;

    /**
     * @return InterfaceType
     * @throws InvariantViolation
     */
    public function build(): InterfaceType
    {
        return new InterfaceType(\array_filter([
            'name'              => $this->reflection->getName(),
            'description'       => $this->reflection->getDescription(),
            'deprecationReason' => $this->reflection->getDeprecationReason(),
            'fields'            => $this->getFields(),
            'resolveType'       => $this->getTypeResolver(),
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
}
