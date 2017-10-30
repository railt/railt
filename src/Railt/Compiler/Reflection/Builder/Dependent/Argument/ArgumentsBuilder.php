<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Dependent\Argument;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Builder\Dependent\ArgumentBuilder;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Trait ArgumentsBuilder
 *
 * @mixin Compiler
 */
trait ArgumentsBuilder
{
    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function compileArgumentsBuilder(TreeNode $ast): bool
    {
        /** @var TypeDefinition $this */
        switch ($ast->getId()) {
            case '#Argument':
                $argument = new ArgumentBuilder($ast, $this->getDocument(), $this);

                $this->arguments = $this->getValidator()->uniqueDefinitions($this->arguments, $argument);

                return true;
        }

        return false;
    }
}
