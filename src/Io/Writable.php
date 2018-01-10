<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io;

/**
 * Interface Writable
 */
interface Writable extends Readable
{
    /**
     * @param string $content
     * @return Writable
     */
    public function update(string $content): self;
}
