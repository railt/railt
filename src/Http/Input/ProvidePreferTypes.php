<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

/**
 * Interface ProvidePreferTypes
 */
interface ProvidePreferTypes
{
    /**
     * @return iterable|string[]
     */
    public function getPreferTypes(): iterable;

    /**
     * @return string
     */
    public function getPreferType(): string;

    /**
     * @param string ...$types
     * @return ProvidePreferTypes|$this
     */
    public function withPreferType(string ...$types): self;

    /**
     * @param string ...$types
     * @return ProvidePreferTypes|$this
     */
    public function setPreferType(string ...$types): self;

    /**
     * @param string $type
     * @return bool
     */
    public function wantsType(string $type): bool;
}
