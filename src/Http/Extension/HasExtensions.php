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
 * Trait HasExtensions
 * @mixin ProvidesExtensions
 */
trait HasExtensions
{
    /**
     * @var array
     */
    protected $extensions = [];

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param iterable|ExtensionInterface[] $extensions
     * @return ProvidesExtensions|$this
     */
    public function withExtensions(iterable $extensions): ProvidesExtensions
    {
        foreach ($extensions as $key => $value) {
            if ($value instanceof ExtensionInterface) {
                $this->withExtension($value);
            } else {
                $this->withExtension($key, $value);
            }
        }

        return $this;
    }

    /**
     * @param string|int|bool|float|ExtensionInterface $keyOrExtension
     * @param ExtensionInterface|\JsonSerializable|mixed $value
     * @return ProvidesExtensions|$this
     */
    public function withExtension($keyOrExtension, $value = null): ProvidesExtensions
    {
        if ($keyOrExtension instanceof \JsonSerializable) {
            $this->extensions = \array_merge_recursive($this->extensions, (array)$keyOrExtension->jsonSerialize());

            return $this;
        }

        if ($keyOrExtension instanceof ExtensionInterface) {
            $this->extensions = \array_merge_recursive($this->extensions, $keyOrExtension->getValue());

            return $this;
        }

        $value = $value instanceof ExtensionInterface ? $value->getValue() : $value;

        $this->extensions[$keyOrExtension] = $value;

        return $this;
    }

    /**
     * @param mixed $key
     * @return ExtensionInterface|null
     */
    public function getExtension($key): ?ExtensionInterface
    {
        return $this->extensions[$key] ?? null;
    }
}
