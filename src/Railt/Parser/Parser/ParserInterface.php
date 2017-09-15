<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Parser;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Support\Filesystem\ReadableInterface;

/**
 * Interface ParserInterface
 * @package Railt\Parser\Parser
 */
interface ParserInterface
{
    /**
     * @param ReadableInterface $sources
     * @return TreeNode
     */
    public function parse(ReadableInterface $sources): TreeNode;
}
