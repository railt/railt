<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Builder;

use Railt\Compiler\Parser\Rule\Symbol;

/**
 * Interface Buildable
 */
interface Buildable
{
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param int|string $to
     * @return Buildable
     */
    public function move($to): self;

    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @return Symbol
     */
    public function toRule(): Symbol;
}
