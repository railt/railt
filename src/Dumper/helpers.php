<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Dumper\TypeDumper;

if (! \function_exists('\\dump')) {
    /**
     * @param mixed $value
     * @return string
     */
    function dump($value): string
    {
        return TypeDumper::render($value);
    }
}

if (! \function_exists('\\dump_type')) {
    /**
     * @param mixed $value
     * @return string
     */
    function dump_type($value): string
    {
        return TypeDumper::renderType($value);
    }
}

if (! \function_exists('\\dump_value')) {
    /**
     * @param mixed $value
     * @return string
     */
    function dump_value($value): string
    {
        return TypeDumper::renderValue($value);
    }
}
