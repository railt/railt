<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Extension;

use Ramsey\Collection\CollectionInterface;
use Ramsey\Collection\Map\TypedMapInterface;

/**
 * Trait ExtensionsTrait
 *
 * @mixin ExtensionsProviderInterface
 */
trait ExtensionsTrait
{
    /**
     * @var TypedMapInterface
     */
    protected TypedMapInterface $extensions;

    /**
     * @param array|iterable|CollectionInterface $extensions
     * @return void
     */
    protected function setExtensions(iterable $extensions = []): void
    {
        if ($extensions instanceof CollectionInterface) {
            $extensions = $extensions->toArray();
        }

        $this->extensions = new ExtensionsCollection($extensions);
    }

    /**
     * @return TypedMapInterface|mixed[]
     */
    public function getExtensions(): TypedMapInterface
    {
        return $this->extensions;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return ExtensionsProviderInterface
     */
    public function withExtension(string $name, $value): ExtensionsProviderInterface
    {
        $self = clone $this;

        $self->extensions->put($name, $value);

        return $self;
    }

    /**
     * @param iterable|CollectionInterface[] $extensions
     * @return ExtensionsProviderInterface
     */
    public function withExtensions(iterable $extensions): ExtensionsProviderInterface
    {
        $self = clone $this;

        foreach ($extensions as $name => $value) {
            $self->extensions->put($name, $value);
        }

        return $self;
    }
}
