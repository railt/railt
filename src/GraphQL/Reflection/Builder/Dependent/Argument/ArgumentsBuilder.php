<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Builder\Dependent\Argument;

use Railt\Compiler\TreeNode;
use Railt\GraphQL\Reflection\Builder\Dependent\ArgumentBuilder;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Trait ArgumentsBuilder
 */
trait ArgumentsBuilder
{
    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    public function compileArgumentsBuilder(TreeNode $ast): bool
    {
        /** @var TypeDefinition $this */
        switch ($ast->getId()) {
            case '#Argument':
                $argument = new ArgumentBuilder($ast, $this->getDocument(), $this);

                $this->arguments = $this->unique($this->arguments, $argument);

                return true;
        }

        return false;
    }
}
