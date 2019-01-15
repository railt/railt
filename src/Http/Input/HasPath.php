<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

/**
 * Trait HasPath
 * @mixin ProvidePath
 */
trait HasPath
{
    /**
     * @var array|string[]
     */
    protected $path = [];

    /**
     * @var array|string[]
     */
    protected $realPath = [];

    /**
     * @param string $path
     * @return string
     */
    public static function pathToParentPath(string $path): string
    {
        return self::chunksToPath(self::chunksToParentChunks(self::pathToChunks($path)));
    }

    /**
     * @param array|string[] $chunks
     * @return string
     */
    public static function chunksToPath($chunks): string
    {
        $chunks = \array_map('\\strval', $chunks);
        $chunks = \array_filter($chunks, '\\strlen');

        return \implode(ProvidePath::PATH_DELIMITER, $chunks);
    }

    /**
     * @param array|string[] $chunks
     * @return array|string[]
     */
    public static function chunksToParentChunks(array $chunks): array
    {
        return \array_slice($chunks, 0, -1);
    }

    /**
     * @param string $path
     * @return array|string[]
     */
    public static function pathToChunks(string $path): array
    {
        \assert(\trim($path) !== '');

        $chunks = \explode(ProvidePath::PATH_DELIMITER, \trim($path));

        \assert(\count($chunks) > 0);

        return $chunks;
    }

    /**
     * @return array|string[]
     */
    public function getPathChunks(): array
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return self::chunksToPath($this->getPathChunks());
    }

    /**
     * @return string
     */
    public function getRealPath(): string
    {
        return self::chunksToPath($this->getRealPathChunks());
    }

    /**
     * @param array|string[] $chunks
     * @return ProvideType|$this
     */
    public function withPathChunks(array $chunks): ProvideType
    {
        $this->path = $chunks;

        return $this;
    }

    /**
     * @return array
     */
    public function getRealPathChunks(): array
    {
        return $this->realPath;
    }

    /**
     * @param array|string[] $chunks
     * @return ProvideType|$this
     */
    public function withRealPathChunks(array $chunks): ProvideType
    {
        $this->realPath = $chunks;

        return $this;
    }
}
