<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Railt\Reflection\Contracts\Definitions\InputDefinition;

/**
 * @property InputDefinition $reflection
 */
class InputBuilder extends TypeBuilder
{
    /**
     * @return Type
     * @throws \InvalidArgumentException
     */
    public function build(): Type
    {
        return new InputObjectType([
            'name'   => $this->reflection->getName(),
            'fields' => ArgumentBuilder::buildArguments($this->reflection, $this->getRegistry()),
        ]);
    }
}
