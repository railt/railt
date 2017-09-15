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
use Hoa\Compiler\Llk\Parser as LlkParser;

/**
 * Class Profiler
 * @package Railt\Parser
 */
class Profiler
{
    /**
     * @var LlkParser
     */
    private $parser;

    /**
     * Profiler constructor.
     * @param LlkParser $parser
     */
    public function __construct(LlkParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    public function dump(TreeNode $ast): string
    {
        $result = (string)(new Dump())->visit($ast);

        $result = str_replace('>  ', '    ', $result);
        $result = preg_replace('/^\s{4}/ium', '', $result);

        $result = preg_replace_callback('/token\((\w+),(.*?)\)/isu', function ($args) {
            return 'token(' . $args[1] . ',' . str_replace(["\n"], ['\n'], $args[2]) . ')';
        }, $result);

        return trim($result);
    }
}
