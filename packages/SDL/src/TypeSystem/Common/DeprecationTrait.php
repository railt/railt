<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Common\DeprecationAwareInterface;

/**
 * @mixin DeprecationAwareInterface
 */
trait DeprecationTrait
{
    /**
     * @var string|null
     */
    public ?string $deprecationReason = null;

    /**
     * @param string|null $message
     * @return void
     */
    protected function setDeprecationReason($message): void
    {
        $this->deprecationReason = $message;

        $this->assertDeprecationTrait();
    }

    /**
     * {@inheritDoc}
     */
    public function getDeprecationReason(): ?string
    {
        $this->assertDeprecationTrait();

        return $this->deprecationReason;
    }

    /**
     * @return void
     */
    protected function assertDeprecationTrait(): void
    {
        \assert(\is_string($this->deprecationReason) || $this->deprecationReason === null);
    }

    /**
     * {@inheritDoc}
     */
    public function isDeprecated(): bool
    {
        $this->assertDeprecationTrait();

        return $this->deprecationReason !== null;
    }
}
