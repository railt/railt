<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Common;

use Railt\Contracts\Common\StringableInterface;

/**
 * @mixin StringableInterface
 */
trait StringableTrait
{
    use JsonableTrait;

    /**
     * @return string
     * @throws \Throwable
     */
    public function __toString(): string
    {
        try {
            return $this->toString();
        } catch (\Throwable $e) {
            \file_put_contents('php://stderr', (string)$e);
            throw $e;
        }
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function toString(): string
    {
        return $this->toJson($this->toJsonDefaultOptions());
    }

    /**
     * @return int
     */
    protected function toJsonDefaultOptions(): int
    {
        $options = 0;
        $options |= \defined('\\JSON_PRETTY_PRINT') ? \JSON_PRETTY_PRINT : 128;
        $options |= \defined('\\JSON_UNESCAPED_UNICODE') ? \JSON_UNESCAPED_UNICODE : 256;

        return $options;
    }
}
