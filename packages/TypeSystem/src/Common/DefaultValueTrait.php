<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Common\DefaultValueAwareInterface;
use Railt\TypeSystem\Value\ValueInterface;
use Serafim\Immutable\Immutable;

/**
 * @mixin DefaultValueAwareInterface
 */
trait DefaultValueTrait
{
    /**
     * @var ValueInterface|null
     */
    protected ?ValueInterface $defaultValue = null;

    /**
     * {@inheritDoc}
     */
    public function getDefaultValue(): ?ValueInterface
    {
        return $this->defaultValue;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param ValueInterface|null $value
     * @return void
     */
    public function setDefaultValue(?ValueInterface $value): void
    {
        $this->defaultValue = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function hasDefaultValue(): bool
    {
        return $this->defaultValue instanceof ValueInterface;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param ValueInterface|null $value
     * @return object|self|$this
     */
    public function withDefaultValue(?ValueInterface $value): self
    {
        return Immutable::execute(fn() => $this->setDefaultValue($value));
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @return object|self|$this
     */
    public function withoutDefaultValue(): self
    {
        return Immutable::execute(fn() => $this->removeDefaultValue());
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @return void
     */
    public function removeDefaultValue(): void
    {
        $this->defaultValue = null;
    }
}
