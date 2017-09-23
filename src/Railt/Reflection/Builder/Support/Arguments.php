<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Support;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Builder\ArgumentBuilder;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Containers\HasArguments;
use Railt\Reflection\Base\Containers\BaseArgumentsContainer;

/**
 * Trait Arguments
 * @mixin HasArguments
 */
trait Arguments
{
    use BaseArgumentsContainer;

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    public function compileArguments(TreeNode $ast): bool
    {
        /** @var Nameable $this */
        if ($ast->getId() === '#Argument') {
            $argument = new ArgumentBuilder($ast, $this->getDocument(), $this);

            $this->arguments[$argument->getName()] = $argument;

            return true;
        }

        return false;
    }
}
