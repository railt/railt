<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Filesystem\ReadableInterface;

/**
 * Interface ParserInterface
 */
interface ParserInterface
{
    /**
     * @param ReadableInterface $sources
     * @return TreeNode
     */
    public function parse(ReadableInterface $sources): TreeNode;
}
