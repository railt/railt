<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Constraint;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\Common\TypeAwareInterface;
use Serafim\Immutable\Immutable;

/**
 * @mixin TypeAwareInterface
 */
trait TypeTrait
{
    /**
     * @var TypeInterface
     */
    protected TypeInterface $type;

    /**
     * {@inheritDoc}
     */
    public function getType(): TypeInterface
    {
        \assert(Constraint::isType($this->type), \vsprintf('%s type must be initialized by the %s', [
            \get_class($this),
            TypeInterface::class,
        ]));

        return $this->type;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeInterface $type
     * @return void
     */
    public function setType(TypeInterface $type): void
    {
        $this->type = $type;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param TypeInterface $type
     * @return object|self|$this
     */
    public function withType(TypeInterface $type): self
    {
        return Immutable::execute(fn() => $this->setType($type));
    }
}
