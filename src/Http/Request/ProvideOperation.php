<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

/**
 * Interface ProvideOperation
 */
interface ProvideOperation
{
    /**
     * @return string|null
     */
    public function getOperation(): ?string;

    /**
     * @param null|string $name
     * @return ProvideOperation|$this
     */
    public function withOperation(?string $name): self;
}
