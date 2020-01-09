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
use Railt\TypeSystem\Argument;
use Railt\SDL\Ast\Definition\ArgumentDefinitionNode;

/**
 * @property ArgumentDefinitionNode $ast
 */
class ArgumentBuilder extends TypeBuilder
{
    /**
     * @return ArgumentInterface|DefinitionInterface
     * @throws \RuntimeException
     */
    public function build(): ArgumentInterface
    {
        $argument = new Argument([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
            'type'        => $this->hint($this->ast->type),
        ]);

        if ($this->ast->defaultValue) {
            return $argument->withDefaultValue($this->ast->defaultValue->toNative());
        }

        return $argument;
    }
}
