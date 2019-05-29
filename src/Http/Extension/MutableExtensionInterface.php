<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Extension;

/**
 * Interface MutableExtensionInterface
 */
interface MutableExtensionInterface extends ExtensionInterface
{
    /**
     * @param string $name
     * @return MutableExtensionInterface|$this
     */
    public function rename(string $name): self;

    /**
     * @param mixed $value
     * @return MutableExtensionInterface|$this
     */
    public function update($value): self;
}
