<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Type\UnionTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;

/**
 * {@inheritDoc}
 */
class UnionType extends NamedType implements UnionTypeInterface
{
    /**
     * @psalm-var array<string, ObjectTypeInterface>
     * @var array|ObjectTypeInterface[]
     */
    public array $types = [];

    /**
     * {@inheritDoc}
     */
    public function hasType(string $name): bool
    {
        return $this->getType($name) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(string $name): ?ObjectTypeInterface
    {
        return $this->types[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getTypes(): iterable
    {
        return $this->types;
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return 'UNION';
    }
}
