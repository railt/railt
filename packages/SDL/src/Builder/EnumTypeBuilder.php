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
use GraphQL\TypeSystem\Type\EnumType;
use Railt\SDL\Ast\Definition\EnumTypeDefinitionNode;

/**
 * @property EnumTypeDefinitionNode $ast
 */
class EnumTypeBuilder extends TypeBuilder
{
    /**
     * @return EnumTypeInterface
     * @throws \RuntimeException
     */
    public function build(): EnumTypeInterface
    {
        $enum = new EnumType([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
        ]);

        $this->register($enum);

        if ($this->ast->values) {
            $enum = $enum->withValues($this->makeAll($this->ast->values));
        }

        return $enum;
    }
}
