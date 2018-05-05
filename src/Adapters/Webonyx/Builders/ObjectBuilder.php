<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Railt\SDL\Contracts\Definitions\ObjectDefinition;

/**
 * @property ObjectDefinition $reflection
 */
class ObjectBuilder extends TypeBuilder
{
    /**
     * @return Type
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function build(): Type
    {
        return new ObjectType([
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'fields'      => function (): array {
                return FieldBuilder::buildFields($this->reflection, $this->getRegistry(), $this->events);
            },
            'interfaces'  => $this->buildInterfaces(),
        ]);
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    private function buildInterfaces(): array
    {
        $result = [];

        foreach ($this->reflection->getInterfaces() as $interface) {
            $result[] = $this->load($interface);
        }

        return $result;
    }
}
