<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\EnumValueInterface;
use GraphQL\Contracts\TypeSystem\Type\EnumTypeInterface;

/**
 * {@inheritDoc}
 */
class EnumType extends NamedType implements EnumTypeInterface
{
    /**
     * @psalm-var array<string, EnumValueInterface>
     * @var array|EnumValueInterface[]
     */
    public array $values = [];

    /**
     * {@inheritDoc}
     */
    public function hasValue(string $name): bool
    {
        return $this->getValue($name) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(string $name): ?EnumValueInterface
    {
        return $this->values[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getValues(): iterable
    {
        return $this->values;
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return 'ENUM';
    }
}
