<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Extension;

use Railt\Dumper\TypeDumper;

/**
 * Trait MutableExtensionProviderTrait
 */
trait MutableExtensionProviderTrait
{
    use ExtensionProviderTrait;

    /**
     * @param array $extensions
     * @return MutableExtensionProviderInterface|$this
     */
    public function setExtensions(array $extensions): MutableExtensionProviderInterface
    {
        $this->extensions = [];

        foreach ($extensions as $k => $v) {
            if (\is_string($k)) {
                $this->withExtension($k, $v);
            } else {
                $this->withExtension($v);
            }
        }

        return $this;
    }

    /**
     * @param string|ExtensionInterface $nameOrExtension
     * @param mixed $value
     * @return ExtensionInterface
     */
    private function resolveExtension($nameOrExtension, $value = null): ExtensionInterface
    {
        switch (true) {
            case \is_string($nameOrExtension):
                return new Extension($nameOrExtension, $value);

            case $nameOrExtension instanceof ExtensionInterface:
                return $nameOrExtension;

            case $value instanceof ExtensionInterface:
                return $value;
        }

        $error = 'First argument should be a name of extension or extension instance, but %s given';
        $error = \sprintf($error, TypeDumper::render($nameOrExtension));

        throw new \InvalidArgumentException($error);
    }

    /**
     * @param string|ExtensionInterface $nameOrExtension
     * @param mixed $value
     * @return MutableExtensionProviderInterface|$this
     */
    public function withExtension($nameOrExtension, $value = null): MutableExtensionProviderInterface
    {
        $extension = $this->resolveExtension($nameOrExtension, $value);

        $this->extensions[$extension->getName()] = $extension;

        return $this;
    }

    /**
     * @param string $name
     * @return MutableExtensionProviderInterface|$this
     */
    public function withoutExtension(string $name): MutableExtensionProviderInterface
    {
        $filter = static function (ExtensionInterface $haystack) use ($name): bool {
            return $haystack->getName() !== $name;
        };

        $this->extensions = \array_filter($this->extensions, $filter);

        return $this;
    }
}
