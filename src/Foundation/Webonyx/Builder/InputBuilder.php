<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\InputObjectType;
use Railt\SDL\Contracts\Definitions\InputDefinition;

/**
 * Class InputBuilder
 * @property InputDefinition $reflection
 */
class InputBuilder extends Builder
{
    /**
     * @return InputObjectType
     * @throws InvariantViolation
     * @throws \Exception
     */
    public function build(): InputObjectType
    {
        return new InputObjectType(\array_filter([
            'name'              => $this->reflection->getName(),
            'description'       => $this->reflection->getDescription(),
            'deprecationReason' => $this->reflection->getDeprecationReason(),
            'fields'            => $this->buildInputFields($this->reflection),
        ]));
    }

    /**
     * @param InputDefinition $input
     * @return array
     * @throws \Exception
     */
    private function buildInputFields(InputDefinition $input): array
    {
        $result = [];

        foreach ($input->getArguments() as $field) {
            if ($this->shouldSkip($field)) {
                continue;
            }

            $item = \array_filter([
                'name'              => $field->getName(),
                'description'       => $field->getDescription(),
                'type'              => $this->buildTypeHint($field),
                'deprecationReason' => $field->getDeprecationReason(),
            ]);

            if ($field->hasDefaultValue()) {
                $item['defaultValue'] = $field->getDefaultValue();
            }

            $result[] = $item;
        }

        return $result;
    }
}
