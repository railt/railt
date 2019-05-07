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
 * Interface ProvideType
 */
interface ProvideType
{
    /**
     * @return string
     */
    public function getTypeName(): string;

    /**
     * @param string $name
     * @return ProvideType|$this
     */
    public function withTypeName(string $name): self;
}
