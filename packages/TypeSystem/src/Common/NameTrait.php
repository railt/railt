<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Common\NameAwareInterface;
use Serafim\Immutable\Immutable;

/**
 * @mixin NameAwareInterface
 */
trait NameTrait
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param string $name
     * @return object|self|$this
     */
    public function withName(string $name): self
    {
        return Immutable::execute(fn() => $this->setName($name));
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        try {
            return $this->getName();
        } catch (\AssertionError $e) {
            return 'unknown';
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        \assert(\is_string($this->name), \get_class($this) . ' name must be initialized');

        return $this->name;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
