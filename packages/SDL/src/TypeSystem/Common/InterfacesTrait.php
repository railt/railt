<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use GraphQL\Contracts\TypeSystem\Common\InterfacesAwareInterface;

/**
 * @mixin InterfacesAwareInterface
 */
trait InterfacesTrait
{
    /**
     * @psalm-var array<string, InterfaceTypeInterface>
     * @var array|InterfaceTypeInterface[]
     */
    public array $interfaces = [];

    /**
     * {@inheritDoc}
     */
    public function hasInterface(string $name): bool
    {
        return $this->getInterface($name) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getInterface(string $name): ?InterfaceTypeInterface
    {
        return $this->interfaces[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function getInterfaces(): iterable
    {
        return $this->interfaces;
    }
}
