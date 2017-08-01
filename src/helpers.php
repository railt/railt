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

if (!function_exists('dump')) {
    /**
     * @param TreeNode $ast
     * @return string
     */
    function dump(TreeNode $ast): string
    {
        $result = (string)(new Dump())->visit($ast);

        $result = str_replace('>  ', '    ', $result);
        $result = preg_replace('/^\s{4}/ium', '', $result);

        return $result;
    }
}
