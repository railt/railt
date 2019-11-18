<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http\Common;

use Railt\Contracts\Common\RenderableInterface;

/**
 * Trait RenderableTrait
 *
 * @mixin RenderableInterface
 */
trait RenderableTrait
{
    use JsonableTrait;

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            return $this->toString();
        } catch (\JsonException $e) {
            return '{"errors": "JSON Error: ' . \addcslashes($e->getMessage(), '"') . '"}';
        }
    }

    /**
     * @return string
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
        return \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE;
    }
}
