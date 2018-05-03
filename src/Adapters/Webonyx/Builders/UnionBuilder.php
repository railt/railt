<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\UnionType;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Definitions\UnionDefinition;

/**
 * @property UnionDefinition $reflection
 */
class UnionBuilder extends TypeBuilder
{
    /**
     * @return Type
     * @throws \InvalidArgumentException
     */
    public function build(): Type
    {
        $types = [];

        /** @var TypeDefinition $type */
        foreach ($this->reflection->getTypes() as $type) {
            $types[] = $this->load($type);
        }

        return new UnionType([
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'types'       => $types,
            'resolveType' => function ($value): ?Type {
                $type = $value['__typename'];

                if (! $type) {
                    throw new \LogicException('Union response should provide the __typename field');
                }

                return $this->load($this->definition($type));
            },
        ]);
    }
}
