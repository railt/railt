<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Input;

use GraphQL\Type\Definition\ResolveInfo;
use Railt\Foundation\Webonyx\Input;

/**
 * Trait PathInfoLoader
 */
trait PathInfoLoader
{
    /**
     * @var bool
     */
    private $initializedPathInfo = false;

    /**
     * @return ResolveInfo
     */
    abstract protected function getResolveInfo(): ResolveInfo;

    /**
     * @return void
     */
    private function bootPathInfoLoader(): void
    {
        if (! $this->initializedPathInfo) {
            $this->initializedPathInfo = true;

            $info = $this->getResolveInfo();

            $this->bootRealPath($info);
            $this->bootPath($info);
            $this->bootAlias($info);
        }
    }

    /**
     * TODO Webonyx does not provide an opportunity to implement this functionality at the moment.
     * TODO Reproduced to Webonyx version < 0.12.6 (including).
     * @see https://github.com/webonyx/graphql-php/issues/412
     *
     * @param ResolveInfo $info
     * @return void
     */
    private function bootRealPath(ResolveInfo $info): void
    {
        $this->withRealPathChunks($info->path);
    }

    /**
     * @param ResolveInfo $info
     * @return void
     */
    private function bootPath(ResolveInfo $info): void
    {
        $this->withPathChunks($info->path);
    }

    /**
     * @param ResolveInfo $info
     */
    private function bootAlias(ResolveInfo $info): void
    {
        $field = \array_last($info->path);

        if ($this->getField() !== $field) {
            $this->withAlias($field);
        }
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        $this->bootPathInfoLoader();

        return $this->alias;
    }

    /**
     * @return array|string[]
     */
    public function getPathChunks(): array
    {
        $this->bootPathInfoLoader();

        return $this->path;
    }

    /**
     * @return array|string[]
     */
    public function getRealPathChunks(): array
    {
        $this->bootPathInfoLoader();

        return $this->realPath;
    }
}
