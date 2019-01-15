<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use GraphQL\Type\Definition\UnionType;
use Railt\Foundation\Webonyx\Builder\Common\TypeResolverTrait;
use Railt\SDL\Contracts\Definitions\UnionDefinition;

/**
 * Class InterfaceBuilder
 * @property UnionDefinition $reflection
 */
class UnionBuilder extends Builder
{
    use TypeResolverTrait;

    /**
     * @return UnionType
     * @throws \GraphQL\Error\Error
     */
    public function build(): UnionType
    {
        return new UnionType(\array_filter([
            'name'              => $this->reflection->getName(),
            'description'       => $this->reflection->getDescription(),
            'deprecationReason' => $this->reflection->getDeprecationReason(),
            'types'             => $this->getTypes(),
            'resolveType'       => $this->getTypeResolver(),
        ]));
    }

    /**
     * @return array
     */
    private function getTypes(): array
    {
        $result = [];

        foreach ($this->reflection->getTypes() as $type) {
            $result[] = $this->loadType($type->getName());
        }

        return $result;
    }
}
