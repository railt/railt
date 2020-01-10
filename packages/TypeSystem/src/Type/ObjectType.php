<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use Railt\TypeSystem\Common\FieldsTrait;
use Railt\TypeSystem\Common\InterfacesTrait;

/**
 * {@inheritDoc}
 */
final class ObjectType extends NamedType implements ObjectTypeInterface
{
    use FieldsTrait;
    use InterfacesTrait;

    /**
     * ObjectType constructor.
     *
     * @param string $name
     * @param iterable $properties
     * @throws \Throwable
     */
    public function __construct(string $name, iterable $properties = [])
    {
        parent::__construct($name, $properties);

        $this->fill($properties, [
            'fields'     => fn(iterable $fields) => $this->addFields($fields),
            'interfaces' => fn(iterable $interfaces) => $this->addInterfaces($interfaces),
        ]);
    }
}
