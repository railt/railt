<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;

/**
 * {@inheritDoc}
 */
abstract class Definition implements DefinitionInterface
{
    /**
     * @param iterable $properties
     * @param array|string[] $allowed
     * @return void
     */
    protected function fill(iterable $properties, array $allowed): void
    {
        foreach ($properties as $name => $value) {
            if (isset($allowed[$name])) {
                $allowed[$name]($value);
            }
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $defaults = ['kind' => $this->getKind()];

        return \array_merge($defaults, \get_object_vars($this));
    }

    /**
     * @return string
     */
    protected function getKind(): string
    {
        $className = \basename(\str_replace('\\', '/', static::class));

        // If this class name ends with the "Type" suffix, then we delete it
        // and return the normal form of type "kind".
        return \substr($className, -4) === 'Type'
            ? \substr($className, 0, -4)
            : $className;
    }
}
