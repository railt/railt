<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use Railt\TypeSystem\Type\ObjectType;
use Railt\TypeSystem\Type\InterfaceType;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\Introspection\Exception\IntrospectionException;
use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;

/**
 * Class StructuredTypeBuilder
 */
abstract class StructuredTypeBuilder extends Builder
{
    /**
     * @var string
     */
    private const ERROR_BAD_IMPLEMENTATION = 'Can not implement non-interface GraphQL type %s';

    /**
     * @param ObjectType|InterfaceType $type
     * {@inheritDoc}
     */
    protected function complete(NamedTypeInterface $type, array $data): void
    {
        foreach ($data['fields'] ?? [] as $field) {
            $type->addField($this->registry->field($field));
        }

        foreach ($data['interfaces'] ?? [] as ['name' => $name]) {
            $interface = $this->get($name);

            if (! $interface instanceof InterfaceTypeInterface) {
                throw new IntrospectionException(
                    \sprintf(self::ERROR_BAD_IMPLEMENTATION, $name)
                );
            }

            $type->addInterface($interface);
        }
    }
}
