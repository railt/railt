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
 * Interface MutablePathProviderInterface
 */
interface MutablePathProviderInterface extends PathProviderInterface
{
    /**
     * @param string|int ...$paths
     * @return MutablePathProviderInterface|$this
     */
    public function withPath(...$paths): self;

    /**
     * @param array $path
     * @return MutablePathProviderInterface|$this
     */
    public function setPath(array $path): self;
}
