<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Request;

/**
 * Trait HasOperation
 * @mixin ProvideOperation
 */
trait HasOperation
{
    /**
     * @var string|null
     */
    protected $operation;

    /**
     * @return null|string
     */
    public function getOperation(): ?string
    {
        return $this->operation;
    }

    /**
     * @param null|string $name
     * @return ProvideOperation|$this
     */
    public function withOperation(?string $name): ProvideOperation
    {
        $this->operation = $name;

        return $this;
    }
}
