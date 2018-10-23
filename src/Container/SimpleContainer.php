<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

use Psr\Container\ContainerInterface as PsrContainer;
use Railt\Container\Exception\ContainerResolutionException;

/**
 * Class SimpleContainer
 */
class SimpleContainer implements PsrContainer
{
    /**
     * @var array
     */
    private $values = [];

    /**
     * @var null|PsrContainer
     */
    private $container;

    /**
     * SimpleContainer constructor.
     * @param PsrContainer|null $container Fallback container
     */
    public function __construct(PsrContainer $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param int|float|string|bool $id Identifier of the entry to look for.
     * @param mixed $value Entry.
     * @return PsrContainer
     */
    public function set($id, $value): PsrContainer
    {
        \assert(\is_scalar($id), 'Invalid format of the entry identifier.');

        $this->values[$id] = $value;

        return $this;
    }

    /**
     * Returns `true` if the container can return an entry for the given identifier.
     * Returns `false` otherwise.
     *
     * @param int|float|string|bool $id Identifier of the entry to look for.
     * @return bool
     */
    public function has($id): bool
    {
        \assert(\is_scalar($id), 'Invalid format of the entry identifier.');

        return \array_key_exists($id, $this->values) || $this->hasInProxy($id);
    }

    /**
     * @param int|float|string|bool $id
     * @return bool
     */
    private function hasInProxy($id): bool
    {
        return $this->container && $this->container->has((string)$id);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param int|float|string|bool $id Identifier of the entry to look for.
     * @return mixed Desired entry.
     * @throws ContainerResolutionException No entry was found for **this** identifier.
     */
    public function get($id)
    {
        \assert(\is_scalar($id), 'Invalid format of the entry identifier.');

        if (\array_key_exists($id, $this->values)) {
            return $this->values[$id];
        }

        if ($this->hasInProxy($id)) {
            return $this->container->get((string)$id);
        }

        throw new ContainerResolutionException(\sprintf('Entry with id %s not found', $id));
    }
}
