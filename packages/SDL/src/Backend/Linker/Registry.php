<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Linker;

use GraphQL\Contracts\TypeSystem\SchemaInterface;

/**
 * Class Registry
 */
final class Registry implements LinkerInterface
{
    /**
     * @var array|callable[]
     */
    private array $loaders = [];

    /**
     * @param string $type
     * @param SchemaInterface $context
     * @return void
     * @throws \LogicException
     */
    public function fetch(string $type, SchemaInterface $context)
    {
        throw new \LogicException('Not implemented yet');
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaders(): iterable
    {
        return $this->loaders;
    }

    /**
     * @param callable $loader
     * @return $this
     */
    public function autoload(callable $loader): self
    {
        $this->loaders[] = $loader;

        return $this;
    }

    /**
     * @param callable $loader
     * @return $this
     */
    public function cancelAutoload(callable $loader): self
    {
        $this->loaders = \array_filter($this->loaders, static function (callable $haystack) use ($loader): bool {
            return $haystack !== $loader;
        });

        return $this;
    }
}
