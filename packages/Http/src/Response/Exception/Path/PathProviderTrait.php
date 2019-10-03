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
 * Trait PathProviderTrait
 */
trait PathProviderTrait
{
    /**
     * @var array|string[]|int[]
     */
    protected array $path = [];

    /**
     * @return array|string[]|int[]
     */
    public function getPath(): array
    {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function hasPath(): bool
    {
        return \count($this->path) > 0;
    }
}
