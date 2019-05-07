<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

/**
 * Interface JsonRuntimeInterface
 */
interface JsonRuntimeInterface extends OptionsInterface
{
    /**
     * User specified recursion depth default value.
     *
     * @var int
     */
    public const DEFAULT_RECURSION_DEPTH = 64;

    /**
     * @return int
     */
    public function getRecursionDepth(): int;

    /**
     * @param int $depth
     * @return JsonRuntimeInterface|$this
     */
    public function withRecursionDepth(int $depth): self;
}
