<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser\Debug;

/**
 * Interface Dumper
 */
interface Dumper
{
    /**
     * @return string
     */
    public function toString(): string;
}
