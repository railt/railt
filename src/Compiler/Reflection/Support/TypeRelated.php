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
use Serafim\Railgun\Compiler\Exceptions\NotReadableException;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;
use Serafim\Railgun\Compiler\Reflection\Definition;
use Serafim\Railgun\Compiler\Exceptions\SemanticException;
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;

/**
 * Trait TypeRelated
 * @package Serafim\Railgun\Compiler\Reflection\Support
 */
trait TypeRelated
{
    /**
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     * @return Definition
     * @throws SemanticException
     * @throws TypeNotFoundException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     * @throws NotReadableException
     * @throws UnexpectedTokenException
     */
    private function loadRelation(TreeNode $ast, Dictionary $dictionary): Definition
    {
        $node = $ast->getChild(0);

        if ($node === null) {
            throw new SemanticException('Broken relation');
        }

        $type = $node->getValueValue();

        return $dictionary->get($type);
    }
}
