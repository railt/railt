<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpExtension;

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
                continue;
            }

            $this->withExtension($v);
        }

        return $this;
    }

    /**
     * @param string|ExtensionInterface $nameOrExtension
     * @param mixed $value
     * @return MutableExtensionProviderInterface|$this
     */
    public function withExtension($nameOrExtension, $value = null): MutableExtensionProviderInterface
    {
        switch (true) {
            case \is_string($nameOrExtension):
                $this->extensions[] = new Extension($nameOrExtension, $value);
                break;

            case $nameOrExtension instanceof ExtensionInterface:
                $this->extensions[] = $nameOrExtension;
                break;

            default:
                $error = 'First argument should be a name of extension or extension instance';
                throw new \InvalidArgumentException($error);
        }

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
