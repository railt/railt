<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\InputFieldInterface;
use Railt\TypeSystem\InputField;
use Railt\SDL\Ast\Definition\InputFieldDefinitionNode;

/**
 * @property InputFieldDefinitionNode $ast
 */
class InputFieldBuilder extends TypeBuilder
{
    /**
     * @return InputFieldInterface|DefinitionInterface
     * @throws \RuntimeException
     */
    public function build(): InputFieldInterface
    {
        $argument = new InputField([
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
