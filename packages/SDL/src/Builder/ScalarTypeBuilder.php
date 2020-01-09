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
use GraphQL\Contracts\TypeSystem\Type\ScalarTypeInterface;
use Railt\TypeSystem\Type\ScalarType;
use Railt\SDL\Ast\Definition\ScalarTypeDefinitionNode;

/**
 * @property ScalarTypeDefinitionNode $ast
 */
class ScalarTypeBuilder extends TypeBuilder
{
    /**
     * @return ScalarTypeInterface|DefinitionInterface
     */
    public function build(): ScalarTypeInterface
    {
        $scalar = new ScalarType([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
        ]);

        $this->register($scalar);

        return $scalar;
    }
}
