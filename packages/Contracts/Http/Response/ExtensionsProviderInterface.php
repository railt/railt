<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Contracts\Http\Response;

/**
 * Interface ExtensionsProviderInterface
 */
interface ExtensionsProviderInterface
{
    /**
     * @return array|mixed[]
     */
    public function getExtensions(): array;

    /**
     * @param string $name
     * @param mixed|string|int|float|bool $value
     * @return MutableExtensionsProviderInterface|$this
     */
    public function withExtension(string $name, $value): self;

    /**
     * @param iterable|iterable $extensions
     * @return MutableExtensionsProviderInterface|$this
     */
    public function withExtensions(iterable $extensions): self;

    /**
     * @return bool
     */
    public function hasExtensions(): bool;
}
