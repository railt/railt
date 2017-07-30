<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler\Reflection\Support;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Dictionary;

/**
 * Trait HasFields
 * @package Serafim\Railgun\Compiler\Reflection\Support
 */
trait HasFields
{
    /**
     * @var array
     */
    private $fields = [];

    /**
     * @param TreeNode $node
     * @param Dictionary $dictionary
     */
    private function compileFields(TreeNode $node, Dictionary $dictionary): void
    {
        if ($node->getId() === '#Field') {
            // TODO
        }
    }
}
