<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\Type;
use Railt\Reflection\Contracts\Definitions\EnumDefinition;

/**
 * @property EnumDefinition $reflection
 */
class EnumBuilder extends TypeBuilder
{
    /**
     * @return Type
     * @throws \InvalidArgumentException
     */
    public function build(): Type
    {
        $values = [];

        foreach ($this->reflection->getValues() as $value) {
            $values[$value->getName()] = [
                'value'       => $value->getValue(),
                'description' => $value->getDescription(),
            ];
        }

        return new EnumType([
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'values'      => $values,
        ]);
    }
}
