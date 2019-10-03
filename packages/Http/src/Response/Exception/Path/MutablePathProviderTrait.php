<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception\Path;

/**
 * Trait MutablePathProviderTrait
 */
trait MutablePathProviderTrait
{
    use PathProviderTrait;

    /**
     * @param array $path
     * @return MutablePathProviderInterface|$this
     */
    public function setPath(array $path): MutablePathProviderInterface
    {
        $this->path = [];

        return $this->withPath(...$path);
    }

    /**
     * @param mixed ...$paths
     * @return MutablePathProviderInterface|$this
     */
    public function withPath(...$paths): MutablePathProviderInterface
    {
        foreach ($paths as $path) {
            \assert(\is_string($path) || \is_int($path));

            $this->path[] = $path;
        }

        return $this;
    }
}
