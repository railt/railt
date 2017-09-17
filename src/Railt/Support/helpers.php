<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Support\Tuple;

if (! function_exists('tuple')) {
    /**
     * @param array ...$params
     * @return array|\ArrayAccess|Tuple
     */
    function tuple(...$params): Tuple
    {
        return new Tuple(...$params);
    }
}
