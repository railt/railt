<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\Contracts\TypeSystem\ArgumentInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Ast\Definition\InputValueDefinitionNode;
use Railt\SDL\TypeSystem\Argument;

/**
 * @property InputValueDefinitionNode $ast
 */
class ArgumentBuilder extends TypeBuilder
{
    /**
     * @return ArgumentInterface|DefinitionInterface
     */
    public function build(): ArgumentInterface
    {
        $argument = new Argument();
        $argument->name = $this->ast->name->value;

        $argument->description = $this->description($this->ast->description);
        $argument->type = $this->buildType($this->ast->type);

        if ($this->ast->defaultValue) {
            $argument->defaultValue = $this->ast->defaultValue->toNative();
            $argument->hasDefaultValue = true;
        }

        return $argument;
    }
}
