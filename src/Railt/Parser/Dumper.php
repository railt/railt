<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

use Hoa\Compiler\Llk\TreeNode;
use Hoa\Compiler\Visitor\Dump;

class Dumper
{
    /**
     * @var TreeNode
     */
    private $ast;

    /**
     * Dumper constructor.
     * @param TreeNode $ast
     */
    public function __construct(TreeNode $ast)
    {
        $this->ast = $ast;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        $result = (string)(new Dump())->visit($this->ast);

        $result = str_replace('>  ', '    ', $result);
        $result = preg_replace('/^\s{4}/ium', '', $result);

        $result = preg_replace_callback('/token\((\w+),(.*?)\)/isu', function ($args) {
            return 'token(' . $args[1] . ',' . str_replace(["\n"], ['\n'], $args[2]) . ')';
        }, $result);

        return trim($result);
    }
}
