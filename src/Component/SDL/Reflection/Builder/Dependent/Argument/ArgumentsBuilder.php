<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Builder\Dependent\Argument;

use Railt\Component\Parser\Ast\NodeInterface;
use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;
use Railt\Component\SDL\Reflection\Builder\Dependent\ArgumentBuilder;

/**
 * Trait ArgumentsBuilder
 */
trait ArgumentsBuilder
{
    /**
     * @param NodeInterface $ast
     * @return bool
     * @throws \Railt\Component\SDL\Exceptions\TypeConflictException
     */
    public function compileArgumentsBuilder(NodeInterface $ast): bool
    {
        /** @var TypeDefinition $this */
        switch ($ast->getName()) {
            case 'Argument':
                $argument = new ArgumentBuilder($ast, $this->getDocument(), $this);

                $this->arguments = $this->unique($this->arguments, $argument);

                return true;
        }

        return false;
    }
}
