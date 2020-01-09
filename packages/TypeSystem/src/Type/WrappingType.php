<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Constraint;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\Type\WrappingTypeInterface;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
abstract class WrappingType extends Type implements WrappingTypeInterface
{
    /**
     * @var TypeInterface
     */
    protected TypeInterface $ofType;

    /**
     * {@inheritDoc}
     */
    public function getOfType(): TypeInterface
    {
        \assert(Constraint::isType($this->ofType), \vsprintf('%s wrapping type must be initialized by the %s', [
            \get_class($this),
            TypeInterface::class,
        ]));

        return $this->ofType;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeInterface $type
     * @return void
     */
    public function setOfType(TypeInterface $type): void
    {
        $this->ofType = $type;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param TypeInterface $type
     * @return object|self|$this
     */
    public function withOfType(TypeInterface $type): self
    {
        return Immutable::execute(fn() => $this->ofType = $type);
    }
}
