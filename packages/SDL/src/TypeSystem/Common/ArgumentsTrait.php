<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\ArgumentInterface;
use GraphQL\Contracts\TypeSystem\Common\ArgumentsAwareInterface;

/**
 * @mixin ArgumentsAwareInterface
 */
trait ArgumentsTrait
{
    /**
     * @psalm-var array<string, ArgumentInterface>
     * @var array|ArgumentInterface[]
     */
    public array $arguments = [];

    /**
     * {@inheritDoc}
     */
    public function hasArgument(string $name): bool
    {
        return $this->getArgument($name) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getArgument(string $name): ?ArgumentInterface
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function getArguments(): iterable
    {
        return $this->arguments;
    }
}
