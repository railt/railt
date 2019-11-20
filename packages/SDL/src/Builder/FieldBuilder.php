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
use GraphQL\Contracts\TypeSystem\FieldInterface;
use GraphQL\TypeSystem\Field;
use Railt\SDL\Ast\Definition\FieldDefinitionNode;

/**
 * @property FieldDefinitionNode $ast
 */
class FieldBuilder extends TypeBuilder
{
    /**
     * @return FieldInterface|DefinitionInterface
     * @throws \RuntimeException
     */
    public function build(): FieldInterface
    {
        $field = new Field([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
            'type'        => $this->hint($this->ast->type),
        ]);

        if ($this->ast->arguments) {
            return $field->withArguments($this->makeAll($this->ast->arguments));
        }

        return $field;
    }
}
