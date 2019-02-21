<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Dumper\TypeDumper;
use Railt\Dumper\VarDumper;


if (! \function_exists('\\dump_type')) {
    /**
     * @param mixed $value
     * @return string
     */
    function dump_type($value): string
    {
        return TypeDumper::getInstance()->dump($value);
    }
}


if (! \function_exists('\\dump_value')) {
    /**
     * @param mixed $value
     * @return string
     */
    function dump_value($value): string
    {
        return VarDumper::getInstance()->dump($value);
    }
}


if (! \function_exists('\\dump_trace')) {
    /**
     * @param string $function
     * @return string
     */
    function dump_trace(string $function): string
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);

        ['file' => $file, 'line' => $line] = $trace[1] ?? $trace[0];

        $message = $function . '() from ' . $file . ':' . $line;

        return \PHP_SAPI === 'cli' ? $message . "\n" : '<pre>' . $message . '</pre>';
    }
}
