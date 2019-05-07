<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Input;

/**
 * Interface ProvidePath
 */
interface ProvidePath
{
    /**
     * @var string
     */
    public const PATH_DELIMITER = '.';

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getRealPath(): string;

    /**
     * @return array|string[]
     */
    public function getPathChunks(): array;

    /**
     * @param array|string[] $chunks
     * @return ProvideType|$this
     */
    public function withPathChunks(array $chunks): ProvideType;

    /**
     * @return array|string[]
     */
    public function getRealPathChunks(): array;

    /**
     * @param array|string[] $chunks
     * @return ProvideType|$this
     */
    public function withRealPathChunks(array $chunks): ProvideType;
}
