<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\TypeSystem\Type\InputObjectType;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\Type\InputTypeInterface;
use Railt\SDL\Ast\Definition\InputObjectTypeDefinitionNode;

/**
 * @property-read InputObjectTypeDefinitionNode $ast
 */
class InputObjectTypeBuilder extends TypeBuilder
{
    /**
     * @return DefinitionInterface|InputTypeInterface
     */
    public function build(): InputTypeInterface
    {
        $input = new InputObjectType([
            'name' => $this->ast->name->value,
        ]);

        $this->registerType($input);

        $input->description = $this->value($this->ast->description);

        // TODO Add input arguments builder

        return $input;
    }
}
