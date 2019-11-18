<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http\Response;

use Railt\Contracts\Http\Response\ExtensionsProviderInterface;
use Railt\Contracts\Http\Response\MutableExtensionsProviderInterface;

/**
 *
 * @mixin ExtensionsProviderInterface
 */
trait ExtensionsTrait
{
    /**
     * @var array
     */
    protected array $extensions = [];

    /**
     * @param array|iterable $extensions
     * @return void
     */
    protected function setExtensions(iterable $extensions = []): void
    {
        foreach ($extensions as $key => $value) {
            $this->extensions[$key] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * {@inheritDoc}
     */
    public function withExtension(string $name, $value): ExtensionsProviderInterface
    {
        return $this->withExtensions([$name => $value]);
    }

    /**
     * {@inheritDoc}
     */
    public function withExtensions(iterable $extensions): ExtensionsProviderInterface
    {
        $self = $this instanceof \Throwable ? $this : clone $this;
        $self->setExtensions($extensions);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function hasExtensions(): bool
    {
        return $this->extensions !== [];
    }
}
