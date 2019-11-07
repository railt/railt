<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\InputFieldInterface;
use GraphQL\Contracts\TypeSystem\Type\InputObjectTypeInterface;

/**
 * {@inheritDoc}
 */
class InputObjectType extends NamedType implements InputObjectTypeInterface
{
    /**
     * @psalm-var array<string, InputFieldInterface>
     * @var array|InputFieldInterface[]
     */
    public array $fields = [];

    /**
     * {@inheritDoc}
     */
    public function hasField(string $name): bool
    {
        return $this->getField($name) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getField(string $name): ?InputFieldInterface
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getFields(): iterable
    {
        return $this->fields;
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return 'INPUT_OBJECT';
    }
}
