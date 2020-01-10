<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\Contracts\TypeSystem\Type\EnumTypeInterface;
use Railt\SDL\Ast\Definition\EnumTypeDefinitionNode;
use Railt\TypeSystem\Type\EnumType;

/**
 * @property EnumTypeDefinitionNode $ast
 */
class EnumTypeBuilder extends TypeBuilder
{
    /**
     * @return EnumTypeInterface
     */
    public function build(): EnumTypeInterface
    {
        $enum = new EnumType([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
        ]);

        $this->register($enum);

        if ($this->ast->values) {
            $enum->setValues($this->makeAll($this->ast->values));
        }

        return $enum;
    }
}
