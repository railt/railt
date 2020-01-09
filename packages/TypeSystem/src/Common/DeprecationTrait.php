<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Common\DeprecationAwareInterface;
use Serafim\Immutable\Immutable;

/**
 * @mixin DeprecationAwareInterface
 */
trait DeprecationTrait
{
    /**
     * @var string|null
     */
    protected ?string $deprecationReason = null;

    /**
     * {@inheritDoc}
     */
    public function getDeprecationReason(): ?string
    {
        return $this->deprecationReason;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param string|null $message
     * @return void
     */
    public function setDeprecationReason(?string $message): void
    {
        $this->deprecationReason = $message;
    }

    /**
     * {@inheritDoc}
     */
    public function isDeprecated(): bool
    {
        return $this->deprecationReason !== null;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param string|null $message
     * @return object|self|$this
     */
    public function withDeprecationReason(?string $message): self
    {
        return Immutable::execute(fn() => $this->setDeprecationReason($message));
    }
}
