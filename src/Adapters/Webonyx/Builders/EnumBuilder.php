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
use Railt\SDL\Contracts\Definitions\EnumDefinition;

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
            $config = [
                'description' => $value->getDescription(),
                'value'       => $value->getValue(),
            ];

            if ($value->isDeprecated()) {
                $config['deprecationReason'] = $value->getDeprecationReason();
            }

            $values[$value->getName()] = $config;
        }

        return new EnumType([
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'values'      => $values,
        ]);
    }
}
