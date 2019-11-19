<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Common;

use Railt\Contracts\Common\JsonableInterface;

/**
 * @mixin JsonableInterface
 */
trait JsonableTrait
{
    use ArrayableTrait;

    /**
     * @param int $options
     * @return string
     * @throws \RuntimeException
     */
    public function toJson(int $options = 0): string
    {
        if (! \function_exists('json_encode')) {
            throw new \RuntimeException('Can not execute method, PHP JSON extension (ext-json) required');
        }

        return \json_encode($this, $options | \JSON_THROW_ON_ERROR);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
