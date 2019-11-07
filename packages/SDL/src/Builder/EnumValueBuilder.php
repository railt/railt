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
use Railt\SDL\Ast\Definition\EnumValueDefinitionNode;
use Railt\SDL\TypeSystem\EnumValue;

/**
 * @property EnumValueDefinitionNode $ast
 */
class EnumValueBuilder extends TypeBuilder
{
    /**
     * @return DefinitionInterface
     */
    public function build(): DefinitionInterface
    {
        $value = new EnumValue();
        $value->name = $this->ast->name->value;
        $value->value = $value->name;

        $value->description = $this->description($this->ast->description);

        return $value;
    }
}
