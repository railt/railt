<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Input;

/**
 * Interface ProvideField
 */
interface ProvideField
{
    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @param string $field
     * @return ProvideField|$this
     */
    public function withField(string $field): self;

    /**
     * @return string|null
     */
    public function getAlias(): ?string;

    /**
     * @param null|string $alias
     * @return ProvideField|$this
     */
    public function withAlias(?string $alias): self;

    /**
     * @return bool
     */
    public function hasAlias(): bool;
}
