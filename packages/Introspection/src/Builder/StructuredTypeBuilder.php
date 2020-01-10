<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\TypeSystem\Type\InterfaceType;
use Railt\TypeSystem\Type\ObjectType;

/**
 * Class StructuredTypeBuilder
 */
abstract class StructuredTypeBuilder extends Builder
{
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
            $type->addInterface($this->reference($name));
        }
    }
}
