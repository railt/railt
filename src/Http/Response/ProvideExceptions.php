<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

use Railt\Support\Debug\Debuggable;

/**
 * Interface ProvideExceptions
 */
interface ProvideExceptions extends Debuggable
{
    /**
     * @return array|\Throwable[]
     */
    public function getExceptions(): array;

    /**
     * @param \Throwable $exception
     * @return ProvideExceptions|$this
     */
    public function withException(\Throwable $exception): self;

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @return bool
     */
    public function hasErrors(): bool;
}
