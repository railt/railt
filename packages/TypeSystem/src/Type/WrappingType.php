<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\Type\WrappingTypeInterface;
use Railt\TypeSystem\Reference\Reference;
use Railt\TypeSystem\Reference\TypeReferenceInterface;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
abstract class WrappingType extends Type implements WrappingTypeInterface
{
    /**
     * @var TypeReferenceInterface|WrappingTypeInterface
     */
    protected $ofType;

    /**
     * WrappingType constructor.
     *
     * @param TypeReferenceInterface|WrappingTypeInterface $ofType
     * @throws \Throwable
     */
    public function __construct($ofType)
    {
        $this->setOfType($ofType);
    }

    /**
     * {@inheritDoc}
     */
    public function getOfType(): TypeInterface
    {
        return Reference::resolve($this, $this->ofType, TypeInterface::class);
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeReferenceInterface|WrappingTypeInterface $type
     * @return void
     */
    public function setOfType($type): void
    {
        \assert($type instanceof WrappingTypeInterface || $type instanceof TypeReferenceInterface);

        $this->ofType = $type;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param TypeReferenceInterface|WrappingTypeInterface $type
     * @return object|self|$this
     */
    public function withOfType($type): self
    {
        return Immutable::execute(fn() => $this->setOfType($type));
    }
}
