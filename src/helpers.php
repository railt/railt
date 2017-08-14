<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Hoa\Compiler\Llk\TreeNode;
use Hoa\Compiler\Visitor\Dump;
use Illuminate\Support\Debug\Dumper;

if (!function_exists('dump')) {
    /**
     * @param TreeNode|mixed $value
     * @return string
     */
    function dump($value): string
    {
        if ($value instanceof TreeNode) {
            $result = (string)(new Dump())->visit($value);

            $result = str_replace('>  ', '    ', $result);
            $result = preg_replace('/^\s{4}/ium', '', $result);
        } else {
            ob_start();
            (new Dumper())->dump($value);
            $result = ob_get_contents();
            ob_end_clean();
        }

        return $result;
    }
}
