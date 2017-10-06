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

/**
 * Trait Arguments
 */
trait ArgumentsBuilder
{
    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function compileArgumentsBuilder(TreeNode $ast): bool
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
