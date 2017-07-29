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
use Serafim\Railgun\Compiler\Autoloader;
use Serafim\Railgun\Compiler\Exceptions\NotReadableException;
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;
use Serafim\Railgun\Compiler\Reflection\Definition;
use Serafim\Railgun\Compiler\Exceptions\SemanticException;

/**
 * Trait TypeRelated
 * @package Serafim\Railgun\Compiler\Reflection\Support
 */
trait TypeRelated
{
    /**
     * @param TreeNode $ast
     * @param Autoloader $loader
     * @return Definition
     * @throws SemanticException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     * @throws NotReadableException
     * @throws TypeNotFoundException
     * @throws UnexpectedTokenException
     */
    private function loadRelation(TreeNode $ast, Autoloader $loader): Definition
    {
        $node = $ast->getChild(0);

        if ($node === null) {
            throw new SemanticException('Broken relation');
        }

        $type = $node->getValueValue();

        $result = $loader->load($type);

        if ($result === null) {
            $error = 'Type "%s" not found and could not be loaded';
            throw new TypeNotFoundException(sprintf($error, $type));
        }

        return $result;
    }
}
