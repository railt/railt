<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\FieldInterface;
use GraphQL\Contracts\TypeSystem\Common\FieldsAwareInterface;

/**
 * @mixin FieldsAwareInterface
 */
trait FieldsTrait
{
    /**
     * @psalm-var array<string, FieldInterface>
     * @var array|FieldInterface[]
     */
    public array $fields = [];

    /**
     * {@inheritDoc}
     */
    public function getField(string $name): ?FieldInterface
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function hasField(string $name): bool
    {
        return $this->getField($name) !== null;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function getFields(): iterable
    {
        return $this->fields;
    }
}
