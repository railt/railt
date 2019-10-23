<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Common;

use Railt\Contracts\Common\JsonableInterface;
use Railt\Contracts\Common\ArrayableInterface;

/**
 * Trait JsonableTrait
 *
 * @mixin JsonableInterface
 * @mixin ArrayableInterface
 */
trait JsonableTrait
{
    use ArrayableTrait;

    /**
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return (string)\json_encode($this, $options | \JSON_THROW_ON_ERROR);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
