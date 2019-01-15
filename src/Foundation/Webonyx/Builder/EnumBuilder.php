<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use GraphQL\Type\Definition\EnumType;
use Railt\SDL\Contracts\Definitions\EnumDefinition;

/**
 * Class EnumBuilder
 * @property EnumDefinition $reflection
 */
class EnumBuilder extends Builder
{
    /**
     * @return EnumType
     * @throws \GraphQL\Error\Error
     */
    public function build(): EnumType
    {
        return new EnumType(\array_filter([
            'name'              => $this->reflection->getName(),
            'description'       => $this->reflection->getDescription(),
            'deprecationReason' => $this->reflection->getDeprecationReason(),
            'values'            => $this->buildValues($this->reflection),
        ]));
    }

    /**
     * @param EnumDefinition $enum
     * @return array
     */
    private function buildValues(EnumDefinition $enum): array
    {
        $result = [];

        foreach ($enum->getValues() as $value) {
            if ($this->shouldSkip($value)) {
                continue;
            }

            $result[$value->getName()] = \array_filter([
                'value'             => $value->getValue(),
                'description'       => $value->getDescription(),
                'deprecationReason' => $value->getDeprecationReason(),
            ]);
        }

        return $result;
    }
}
