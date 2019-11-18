<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Container\Container;

use Railt\Container\Exception\ContainerResolutionException;
use Railt\Container\Exception\InvalidArgumentException;

/**
 * Trait AliasesTrait
 *
 * @mixin Aliased
 */
trait AliasesTrait
{
    /**
     * The registered type aliases.
     *
     * @var array|string[]
     */
    protected array $aliases = [];

    /**
     * @param string $id
     * @return bool
     */
    abstract public function has($id): bool;

    /**
     * Alias a type to a different name.
     *
     * @param string $locator
     * @param string ...$aliases
     * @return Aliased|$this
     * @throws ContainerResolutionException
     * @throws InvalidArgumentException
     */
    public function alias(string $locator, string ...$aliases): Aliased
    {
        if (\count($aliases) === 0) {
            $error = 'The number of aliases should be greater than zero';
            throw new InvalidArgumentException($error);
        }

        if (! $this->has($locator)) {
            $error = 'Could not to define an alias, because service %s is not defined in the container';
            throw new ContainerResolutionException(\sprintf($error, $locator));
        }

        foreach ($aliases as $alias) {
            $this->aliases[$alias] = $locator;
        }

        return $this;
    }

    /**
     * @param string $id
     * @return string
     */
    protected function locator(string $id): string
    {
        while (! isset($this->aliases[$id])) {
            $id = $this->aliases[$id];
        }

        return $id;
    }
}
