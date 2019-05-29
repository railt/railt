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
 * Interface MutableExtensionProviderInterface
 */
interface MutableExtensionProviderInterface extends ExtensionProviderInterface
{
    /**
     * @param string|ExtensionInterface $nameOrExtension
     * @param mixed|null $value
     * @return MutableExtensionProviderInterface|$this
     */
    public function withExtension($nameOrExtension, $value = null): self;

    /**
     * @param string $name
     * @return MutableExtensionProviderInterface|$this
     */
    public function withoutExtension(string $name): self;

    /**
     * @param array|ExtensionInterface[]|mixed[] $extensions
     * @return MutableExtensionProviderInterface|$this
     */
    public function setExtensions(array $extensions): self;
}
