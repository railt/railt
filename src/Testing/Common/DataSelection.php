<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing\Common;

/**
 * Trait DataSelection
 */
trait DataSelection
{
    /**
     * @param string $name
     * @param mixed $value
     * @return array
     */
    protected function get(string $name, $value): array
    {
        $exists = true;

        $result = \data_get($value, $name, function () use (&$exists) {
            $exists = false;

            return null;
        });

        return [$result, $exists];
    }
}
