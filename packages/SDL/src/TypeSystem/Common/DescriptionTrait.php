<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Common\DescriptionAwareInterface;

/**
 * @mixin DescriptionAwareInterface
 */
trait DescriptionTrait
{
    /**
     * @var string|null
     */
    public ?string $description;

    /**
     * {@inheritDoc}
     */
    public function getDescription(): ?string
    {
        $this->assertDescriptionTrait();

        return $this->description;
    }

    /**
     * @return void
     */
    protected function assertDescriptionTrait(): void
    {
        \assert(\is_string($this->description) || $this->description === null);
    }
}
