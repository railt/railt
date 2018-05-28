<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;
use Railt\SDL\Contracts\Definitions\InterfaceDefinition;

/**
 * @property InterfaceDefinition $reflection
 */
class InterfaceBuilder extends TypeBuilder
{
    /**
     * @return Type
     * @throws \Exception
     */
    public function build(): Type
    {
        return new InterfaceType([
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'fields'      => function (): array {
                return FieldBuilder::buildFields($this->reflection, $this->getRegistry(), $this->events);
            },
            'resolveType' => function ($value): ?Type {
                $type = $value['__typename'];

                if (! $type) {
                    throw new \LogicException('Interface response should provide the __typename field');
                }

                return $this->load($this->definition($type));
            },
        ]);
    }
}
