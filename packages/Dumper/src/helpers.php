<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Dumper\Facade;

if (! \function_exists('\\string')) {
    /**
     * @param mixed $value
     * @return string
     */
    function string($value): string
    {
        return Facade::dump($value);
    }
}
