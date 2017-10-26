<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Process;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\CompilerInterface;

/**
 * Interface Compilable
 */
interface Compilable
{
    /**
     * @return bool
     */
    public function compileIfNotCompiled(): bool;

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool;

    /**
     * @return TreeNode
     */
    public function getAst(): TreeNode;

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface;
}
