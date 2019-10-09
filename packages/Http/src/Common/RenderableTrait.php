<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Common;

/**
 * @mixin RenderableInterface
 */
trait RenderableTrait
{
    /**
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            return (string)\json_encode($this->toArray(), $this->toStringJsonOptions());
        } catch (\JsonException $e) {
            return '{"errors": "JSON Error: ' . \addcslashes($e->getMessage(), '"') . '"}';
        }
    }

    /**
     * @return int
     */
    protected function toStringJsonOptions(): int
    {
        return \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
