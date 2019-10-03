<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

/**
 * Trait RenderableTrait
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
            return \json_encode($this->toArray(), \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return '{}';
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return \array_filter($this->toArray());
    }
}
